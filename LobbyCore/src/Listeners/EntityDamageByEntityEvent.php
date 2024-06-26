<?php

namespace Nyrok\LobbyCore\Listeners;

use Nyrok\LobbyCore\Core;
use Nyrok\LobbyCore\Managers\KnockBackManager;
use Nyrok\LobbyCore\Player\ViperPlayer;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityDamageByEntityEvent as ClassEvent;
use pocketmine\scheduler\ClosureTask;

final class EntityDamageByEntityEvent implements Listener{

    const NAME = "EntityDamageByEntityEvent";

    /**
     * @param ClassEvent $event
     */

    public function onEvent(ClassEvent $event){
        $entity = $event->getEntity();
        $damager = $event->getDamager();
        switch (true){
            case $entity instanceof ViperPlayer:
                $entity->getPlayerProperties()->setNestedProperties("parameters.combo", 0);
                // TODO: NE PAS METTRE DE "break;" MÊME SI IL Y A DES INSTRUCTIONS ICI
            case $damager instanceof ViperPlayer:
                $entity->knockback($damager->getMotion()->x, $damager->getMotion()->z, 1, KnockBackManager::getKnockBackY());
                $event->setKnockBack(KnockBackManager::getKnockBackForce());
                $properties = $damager->getPlayerProperties();
                if($properties->canSend("parameters.reach", true)){
                    $distance = round($entity->getLocation()->distance($damager->getLocation()), 2);
                    $properties->setNestedProperties("parameters.reach", $distance);
                    Core::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use ($properties, $distance): void {
                        if($properties->getNestedProperties("parameters.reach") === $distance){
                            $properties->setNestedProperties("parameters.reach", 0);
                        }
                    }), (1.5*20));
                }

                if($properties->canSend("parameters.combo", true)){
                    $properties->setNestedProperties("parameters.combo", ($combo = $properties->getNestedProperties("parameters.combo") + 1));
                    Core::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use ($properties, $combo): void {
                        if($properties->getNestedProperties("parameters.combo") === $combo){
                            $properties->setNestedProperties("parameters.combo", 0);
                        }
                    }), (5*20));
                }
        }
    }
}