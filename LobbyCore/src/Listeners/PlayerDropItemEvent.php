<?php

namespace Nyrok\LobbyCore\Listeners;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDropItemEvent as ClassEvent;
use pocketmine\permission\DefaultPermissions;

final class PlayerDropItemEvent implements Listener
{
    const NAME = "PlayerDropItemEvent";

    /**
     * @param ClassEvent $event
     */
    public function onEvent(ClassEvent $event){
        if(!$event->getPlayer()->hasPermission(DefaultPermissions::ROOT_OPERATOR)) $event->cancel();
    }
}