<?php

namespace Nyrok\LobbyCore\Listeners;

use Nyrok\LobbyCore\Librairies\refaltor\customitemapi\items\CustomPickaxe;
use Nyrok\LobbyCore\Objects\CustomAxe;
use Nyrok\LobbyCore\Objects\CustomHoe;
use Nyrok\LobbyCore\Objects\CustomShovel;
use Nyrok\LobbyCore\Objects\CustomSword;
use pocketmine\event\Listener;
use pocketmine\event\block\BlockBreakEvent as ClassEvent;
use pocketmine\world\particle\BlockBreakParticle;
use pocketmine\world\sound\BlockBreakSound;

final class BlockBreakEvent implements Listener
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