<?php

namespace Nyrok\LobbyCore\Managers;

use JetBrains\PhpStorm\Pure;
use Nyrok\LobbyCore\Core;
use Nyrok\LobbyCore\Listeners\DataPacketReceiveEvent;
use Nyrok\LobbyCore\Listeners\DataPacketSendEvent;
use Nyrok\LobbyCore\Listeners\PlayerCreationEvent;
use Nyrok\LobbyCore\Listeners\PlayerDropItemEvent;
use Nyrok\LobbyCore\Listeners\PlayerInteractEvent;
use Nyrok\LobbyCore\Listeners\PlayerItemUseEvent;
use Nyrok\LobbyCore\Listeners\PlayerJoinEvent;
use Nyrok\LobbyCore\Listeners\PlayerQuitEvent;
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