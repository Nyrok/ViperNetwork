<?php

namespace Nyrok\LobbyCore\Listeners;

use Nyrok\LobbyCore\Managers\CustomItemManager;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent as ClassEvent;

final class PlayerQuitEvent implements Listener
{
    const NAME = "PlayerQuitEvent";

    /**
     * @param ClassEvent $event
     */
    public function onEvent(ClassEvent $event): void
    {
        $player = $event->getPlayer();
        if (!isset(CustomItemManager::$handlers[$player->getName()])) {
            return;
        }
        foreach (CustomItemManager::$handlers[$player->getName()] as $blockHash => $handler) {
            $handler->cancel();
        }
        unset(CustomItemManager::$handlers[$player->getName()]);
    }

}