<?php

namespace Nyrok\LobbyCore\Listeners;

use pocketmine\event\Listener;
use pocketmine\event\block\BlockBreakEvent as ClassEvent;
use pocketmine\world\particle\BlockBreakParticle;
use pocketmine\world\sound\BlockBreakSound;

class BlockBreakEvent implements Listener
{
    const NAME = "BlockBreakEvent";

    /**
     * @param ClassEvent $event
     */
    public function onEvent(ClassEvent $event): void
    {
        $item = $event->getItem();
        if (
            $item instanceof CustomPickaxe ||
            $item instanceof CustomAxe ||
            $item instanceof CustomShovel ||
            $item instanceof CustomSword ||
            $item instanceof CustomHoe
        ) {
            $event->getBlock()->getPosition()->getWorld()->addSound($event->getBlock()->getPosition(), new BlockBreakSound($event->getBlock()));
            $event->getBlock()->getPosition()->getWorld()->addParticle($event->getBlock()->getPosition(), new BlockBreakParticle($event->getBlock()));
        }
    }

}