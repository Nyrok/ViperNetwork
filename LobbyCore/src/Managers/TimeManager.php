<?php

namespace Nyrok\LobbyCore\Managers;

use Nyrok\LobbyCore\Core;

abstract class TimeManager
{
    public static function initTime(): void {
        foreach (Core::getInstance()->getServer()->getWorldManager()->getWorlds() as $world){
            $world->setTime(0);
            $world->stopTime();
        }
    }
}