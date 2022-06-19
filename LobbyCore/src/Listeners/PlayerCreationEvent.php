<?php

namespace Nyrok\LobbyCore\Listeners;

use Nyrok\LobbyCore\Player\ViperPlayer;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCreationEvent as ClassEvent;

final class PlayerCreationEvent implements Listener{

    const NAME = "PlayerCreationEvent";

    /**
     * @param ClassEvent $event
     */
    public function onEvent(ClassEvent $event){
        $event->setPlayerClass(ViperPlayer::class);
    }
}