<?php

namespace Nyrok\LobbyCore\Listeners;

use Nyrok\LobbyCore\Managers\FFAManager;
use Nyrok\LobbyCore\Managers\FormsManager;
use Nyrok\LobbyCore\Managers\LobbyManager;
use Nyrok\LobbyCore\Player\ViperPlayer;
use Nyrok\LobbyCore\Utils\PlayerUtils;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent as ClassEvent;
use pocketmine\item\ItemIds;

final class PlayerInteractEvent implements Listener
{
    const NAME = "PlayerInteractEvent";

    /**
     * @param ClassEvent $event
     */
    public function onEvent(ClassEvent $event){
        $player = $event->getPlayer();
        if($player instanceof ViperPlayer){
            if(LobbyManager::onSpawn($event->getPlayer()->getPosition())){
                switch ($event->getItem()->getId()){
                    case ItemIds::DIAMOND_SWORD:
                        FFAManager::getFFA()->load($player);
                        break;
                    case ItemIds::COMPASS:
                        FormsManager::modesForm($player);
                        break;
                }
            }
        }
    }
}