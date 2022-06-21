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
        if($damager instanceof ViperPlayer){
            $properties = $damager->getPlayerProperties();
            if(is_numeric($properties->getNestedProperties("parameters.reach"))){
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