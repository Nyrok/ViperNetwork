<?php

namespace Nyrok\LobbyCore\Listeners;

use Nyrok\LobbyCore\Managers\CooldownManager;
use Nyrok\LobbyCore\Managers\FormsManager;
use Nyrok\LobbyCore\Player\ViperPlayer;
use Nyrok\LobbyCore\Utils\PlayerUtils;
use pocketmine\event\player\PlayerItemUseEvent as ClassEvent;
use Nyrok\LobbyCore\Managers\LobbyManager;
use pocketmine\event\Listener;
use pocketmine\item\ItemIds;

final class PlayerItemUseEvent implements Listener
{
    const NAME = "PlayerItemUseEvent";

    /**
     * @param ClassEvent $event
     */
    public function onEvent(ClassEvent $event){
        $player = $event->getPlayer();
        if($player instanceof ViperPlayer){
            if(LobbyManager::onSpawn($event->getPlayer()->getPosition())){
                match ($event->getItem()->getId()){
                    ItemIds::FEATHER => $player->isOnGround() ? PlayerUtils::bumpPlume($player) : null,
                    ItemIds::MINECART_WITH_CHEST => $player->sendForm(FormsManager::parametersUI($player)),
                    default => null
                };
            }

            /** COOLDOWN */
            foreach (CooldownManager::getCooldowns() as $cooldown){
                if($cooldown->getItem()->equals($player->getInventory()->getItemInHand())){
                    if($cooldown->has($player)){
                        $player->getLanguage()->getMessage("messages.cooldown", ["{time}" => ($cooldown->get($player) - time())])->send($player);
                        $event->cancel();
                    }
                    else {
                        $cooldown->set($player);
                    }
                }
            }
        }
    }
}