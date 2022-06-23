<?php

namespace Nyrok\LobbyCore\Listeners;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent as ClassEvent;

final class PlayerDeathEvent implements Listener
{
    const NAME = "PlayerDeathEvent";

    public function onEvent(ClassEvent $event): void {
        $event->setDrops([]);
    }

}