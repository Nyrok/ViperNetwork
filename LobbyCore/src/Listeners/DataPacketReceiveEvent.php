<?php

namespace Nyrok\LobbyCore\Listeners;

use Nyrok\LobbyCore\Utils\PlayerUtils;
use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent as ClassEvent;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\types\LevelSoundEvent;

final class DataPacketReceiveEvent implements Listener
{
    const NAME = "DataPacketReceiveEvent";

    /**
     * @param ClassEvent $event
     */
    public function onEvent(ClassEvent $event){
    }
}