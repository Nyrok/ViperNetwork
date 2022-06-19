<?php

namespace Nyrok\LobbyCore\Listeners;

use Nyrok\LobbyCore\Utils\PlayerUtils;
use pocketmine\event\player\PlayerItemUseEvent as ClassEvent;
use Nyrok\LobbyCore\Managers\LobbyManager;
use pocketmine\event\Listener;
use pocketmine\item\ItemIds;

final class PlayerItemUseEvent implements Listener
{
    const NAME = "PlayerItemUseEvent";

    /**
     * @param ClassEvent $event
     */
    public function onEvent(ClassEvent $event){
        if(LobbyManager::onSpawn($event->getPlayer()->getPosition())){
            match ($event->getItem()->getId()){
                ItemIds::FEATHER => PlayerUtils::bumpPlume($event->getPlayer()),
                default => null
            };
        }
    }


}