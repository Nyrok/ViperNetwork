<?php

namespace Nyrok\LobbyCore\Listeners;

use Nyrok\LobbyCore\Player\ViperPlayer;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent as ClassEvent;

final class PlayerChatEvent implements Listener
{
    const NAME = "PlayerChatEvent";

    /**
     * @param ClassEvent $event
     * @priority LOWEST
     */
    public function onEvent(ClassEvent $event){
        $player = $event->getPlayer();
        if($player instanceof ViperPlayer){
            $event->setFormat($player->getGrade()->getFormat([
                "{player}" => $player->getName(),
                "{chat}" => $event->getMessage(),
                ]));
            $event->setMessage("");
            if($player->getPlayerProperties()->getNestedProperties("status.muted") ?? false){
                $event->cancel();
            }
        }
    }

}