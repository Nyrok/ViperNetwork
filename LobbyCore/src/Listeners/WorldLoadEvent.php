<?php

namespace Nyrok\LobbyCore\Listeners;

use Nyrok\LobbyCore\TimeManager;
use pocketmine\event\Listener;
use pocketmine\event\world\WorldLoadEvent as ClassEvent;

class WorldLoadEvent implements Listener
{
    public function onEvent(ClassEvent $event)
    {
        TimeManager::initTime();
    }
}