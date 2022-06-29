<?php

namespace Nyrok\LobbyCore\Listeners;

use Nyrok\LobbyCore\TimeManager;
use pocketmine\event\Listener;
use pocketmine\event\world\WorldLoadEvent as ClassEvent;

final class WorldLoadEvent implements Listener
{
    const NAME = "WorldLoadEvent";

    public function onEvent(ClassEvent $event)
    {
        TimeManager::initTime();
    }
}