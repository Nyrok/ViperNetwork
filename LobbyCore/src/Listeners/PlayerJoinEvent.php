<?php
namespace Nyrok\LobbyCore\Listeners;

use Nyrok\LobbyCore\Managers\CustomItemManager;
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
        $player = $event->getPlayer();
        $network = $player->getNetworkSession();
        $packet = CustomItemManager::getPacket();
        if (!is_null($packet)) $network->sendDataPacket(CustomItemManager::getPacket());
        HotbarManager::load($event->getPlayer());
    }

}