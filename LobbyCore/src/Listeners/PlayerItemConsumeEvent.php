<?php

namespace Nyrok\LobbyCore\Listeners;

use Nyrok\LobbyCore\Objects\CustomFood;
use Nyrok\LobbyCore\Objects\CustomPotion;
use pocketmine\event\player\PlayerItemConsumeEvent as ClassEvent;
use pocketmine\event\Listener;

final class PlayerItemConsumeEvent implements Listener
{
    const NAME = "PlayerItemConsumeEvent";

    /**
     * @param ClassEvent $event
     */
    public function onEvent(ClassEvent $event): void
    {
        $item = $event->getItem();
        if ($item instanceof CustomFood || $item instanceof CustomPotion) {
            $item->onConsume($event->getPlayer());
        }
    }

}