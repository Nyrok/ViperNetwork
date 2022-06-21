<?php

namespace Nyrok\LobbyCore\Listeners;

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
                    ItemIds::MINECART_WITH_CHEST => $player->uiManager->parametersUI(),
                    default => null
                };
            }
       }
    }
}