<?php

namespace Nyrok\LobbyCore\Listeners;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerExhaustEvent as ClassEvent;

final class PlayerExhaustEvent implements Listener
{
    const NAME = "PlayerExhaustEvent";

    public function onEvent(ClassEvent $event){
        $event->getPlayer()->getHungerManager()->setSaturation(20);
        $event->getPlayer()->getHungerManager()->setFood($event->getPlayer()->getHungerManager()->getMaxFood());
    }

}