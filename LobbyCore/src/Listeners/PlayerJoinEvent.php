<?php
namespace Nyrok\LobbyCore\Listeners;

use Nyrok\LobbyCore\Managers\HotbarManager;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent as ClassEvent;

final class PlayerJoinEvent implements Listener
{
    const NAME = "PlayerJoinEvent";

    /**
     * @param ClassEvent $event
     */
    public function onEvent(ClassEvent $event){
        HotbarManager::load($event->getPlayer());
    }

}