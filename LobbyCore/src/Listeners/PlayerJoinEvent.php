<?php
namespace Nyrok\LobbyCore\Listeners;

use Nyrok\LobbyCore\Managers\CustomItemManager;
use Nyrok\LobbyCore\Managers\HotbarManager;
use Nyrok\LobbyCore\Player\ViperPlayer;
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
        if($player instanceof ViperPlayer){
            if (!is_null($packet)) $network->sendDataPacket(CustomItemManager::getPacket());
            HotbarManager::load($player);
        }
    }
}