<?php

namespace Nyrok\LobbyCore\Listeners;

use Nyrok\LobbyCore\Managers\KnockBackManager;
use Nyrok\LobbyCore\Player\ViperPlayer;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityDamageByEntityEvent as ClassEvent;

class EntityDamageByEntityEvent implements Listener{

    const NAME = "EntityDamageByEntityEvent";

    /**
     * @param ClassEvent $event
     */

    public function onEvent(ClassEvent $event){
        $entity = $event->getEntity();
        $damager = $event->getDamager();
        switch ($event){
            case $event->getEntity() instanceof ViperPlayer:
                // TODO: NE PAS METTRE DE "break;" MÊME SI IL Y A INSTRUCTIONS ICI
            case $event->getDamager() instanceof ViperPlayer:
                $event->getEntity()->knockback($damager->getMotion()->x, $damager->getMotion()->z, KnockBackManager::getKnockBackForce(), KnockBackManager::getKnockBackY());
                $properties = $damager->getPlayerProperties();
                if($properties->canSend("parameters.reach", true)){
                    $distance = $entity->getPosition()->asVector3()->distance($damager->getPosition()->asVector3());
                    $properties->setNestedProperties("parameters.reach", $distance);
                }
                // TODO: A test
                if($properties->canSend("parameters.combo") && (!$entity->isOnGround() || $properties->getNestedProperties("parameters.combo"))){
                    $properties->setNestedProperties("parameters.combo", $properties->getNestedProperties("parameters.combo") + 1);
                }
        }
    }
}