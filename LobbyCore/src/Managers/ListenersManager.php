<?php

namespace Nyrok\LobbyCore\Managers;

use JetBrains\PhpStorm\Pure;
use Nyrok\LobbyCore\Core;
use Nyrok\LobbyCore\Listeners\BlockBreakEvent;
use Nyrok\LobbyCore\Listeners\DataPacketReceiveEvent;
use Nyrok\LobbyCore\Listeners\DataPacketSendEvent;
use Nyrok\LobbyCore\Listeners\EntityDamageByEntityEvent;
use Nyrok\LobbyCore\Listeners\EntityTeleportEvent;
use Nyrok\LobbyCore\Listeners\PlayerChatEvent;
use Nyrok\LobbyCore\Listeners\PlayerCreationEvent;
use Nyrok\LobbyCore\Listeners\PlayerDeathEvent;
use Nyrok\LobbyCore\Listeners\PlayerDropItemEvent;
use Nyrok\LobbyCore\Listeners\PlayerExhaustEvent;
use Nyrok\LobbyCore\Listeners\PlayerInteractEvent;
use Nyrok\LobbyCore\Listeners\PlayerItemConsumeEvent;
use Nyrok\LobbyCore\Listeners\PlayerItemUseEvent;
use Nyrok\LobbyCore\Listeners\PlayerJoinEvent;
use Nyrok\LobbyCore\Listeners\PlayerQuitEvent;
use Nyrok\LobbyCore\Listeners\ProjectileHitBlockEvent;
use Nyrok\LobbyCore\Listeners\WorldLoadEvent;
use pocketmine\event\Listener;
use pocketmine\plugin\Plugin;

abstract class ListenersManager
{
    /**
     * @return Listener[]
     */
    #[Pure] public static function getListeners(): array {
        return [
            new PlayerJoinEvent(),
            new PlayerInteractEvent(),
            new PlayerDropItemEvent(),
            new DataPacketReceiveEvent(),
            new PlayerItemUseEvent(),
            new DataPacketSendEvent(),
            new PlayerQuitEvent(),
            new PlayerCreationEvent(),
            new BlockBreakEvent(),
            new PlayerItemConsumeEvent(),
            new PlayerExhaustEvent(),
            new EntityDamageByEntityEvent(),
            new PlayerChatEvent(),
            new PlayerDeathEvent(),
            new ProjectileHitBlockEvent(),
            new EntityTeleportEvent(),
            new WorldLoadEvent(),
        ];
    }

    /**
     * @param Plugin $plugin
     */
    public static function initListeners(Plugin $plugin): void {
        foreach (self::getListeners() as $event){
            $plugin->getServer()->getPluginManager()->registerEvents($event, $plugin);
            Core::getInstance()->getLogger()->notice("[LISTENERS] Listener: ".$event::NAME." Loaded");
        }
    }
}