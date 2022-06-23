<?php

namespace Nyrok\LobbyCore\Listeners;

use Nyrok\LobbyCore\Player\ViperPlayer;
use pocketmine\entity\projectile\EnderPearl;
use pocketmine\event\Listener;
use pocketmine\event\entity\ProjectileHitBlockEvent as ClassEvent;

final class ProjectileHitBlockEvent implements Listener
{
    const NAME = "ProjectileHitBlockEvent";

    public function onEvent(ClassEvent $event){
        $player = $event->getEntity();
        if($player instanceof ViperPlayer and $event->getEntity() instanceof EnderPearl){
            $player->smoothPerle($event->getBlockHit()->getPosition());
        }
    }
}