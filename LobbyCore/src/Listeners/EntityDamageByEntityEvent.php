<?php

namespace Nyrok\LobbyCore\Listeners;

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
        if($entity instanceof ViperPlayer && $damager instanceof ViperPlayer){
            $properties = $damager->getPlayerProperties();
            if(!$properties->getNestedProperties("parameters.reach") === false){
                $distance = $entity->getPosition()->asVector3()->distance($damager->getPosition()->asVector3());
                $properties->setNestedProperties("parameters.reach", $distance);
            }
            //A test
            if(!$entity->isOnGround() || $properties->getNestedProperties("parameters.combo")){
                $properties->setNestedProperties("parameters.combo", $properties->getNestedProperties("parameters.combo") + 1);
            }
        }
    }
}