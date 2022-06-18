<?php

namespace Nyrok\LobbyCore\Listeners;

use Nyrok\LobbyCore\Player\ViperPlayer;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCreationEvent as ClassEvent;

class PlayerCreationEvent implements Listener{

    public function onEvent(ClassEvent $event){
        $event->setPlayerClass(ViperPlayer::class);
    }
}