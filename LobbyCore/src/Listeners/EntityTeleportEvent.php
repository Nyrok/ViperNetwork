<?php

namespace Nyrok\LobbyCore\Listeners;

use pocketmine\event\Listener;
use pocketmine\event\entity\EntityTeleportEvent as ClassEvent;

final class EntityTeleportEvent implements Listener
{
    const NAME = "EntityTeleportEvent";

    public function onEvent(ClassEvent $event){

    }
}