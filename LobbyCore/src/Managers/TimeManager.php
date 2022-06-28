<?php

namespace Nyrok\LobbyCore;

abstract class TimeManager
{
    public static function initTime(): void {
        foreach (Core::getInstance()->getServer()->getWorldManager()->getWorlds() as $world){
            $world->setTime(0);
            $world->stopTime();
        }
    }
}