<?php

/*
 *    _______           _______ _________ _______  _______ __________________ _______  _______  _______  _______ _________
 *   (  ____ \|\     /|(  ____ \\__   __/(  ___  )(       )\__   __/\__   __/(  ____ \(       )(  ___  )(  ____ )\__   __/
 *   | (    \/| )   ( || (    \/   ) (   | (   ) || () () |   ) (      ) (   | (    \/| () () || (   ) || (    )|   ) (
 *   | |      | |   | || (_____    | |   | |   | || || || |   | |      | |   | (__    | || || || (___) || (____)|   | |
 *   | |      | |   | |(_____  )   | |   | |   | || |(_)| |   | |      | |   |  __)   | |(_)| ||  ___  ||  _____)   | |
 *   | |      | |   | |      ) |   | |   | |   | || |   | |   | |      | |   | (      | |   | || (   ) || (         | |
 *   | (____/\| (___) |/\____) |   | |   | (___) || )   ( |___) (___   | |   | (____/\| )   ( || )   ( || )      ___) (___
 *   (_______/(_______)\_______)   )_(   (_______)|/     \|\_______/   )_(   (_______/|/     \||/     \||/       \_______/
 *
 *   This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU Lesser General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   By: refaltor
 *   Discord: Refaltor#6969
 */


declare(strict_types=1);

namespace Nyrok\LobbyCore\Librairies\refaltor\customitemapi;

use pocketmine\plugin\PluginBase;
use Nyrok\LobbyCore\Librairies\refaltor\customitemapi\managers\ItemManager;
use Nyrok\LobbyCore\Librairies\refaltor\customitemapi\events\listeners\PacketListeners;
use Nyrok\LobbyCore\Librairies\refaltor\customitemapi\events\listeners\PlayerListeners;
use Nyrok\LobbyCore\Librairies\refaltor\customitemapi\traits\DevUtils;
use Nyrok\LobbyCore\Librairies\refaltor\customitemapi\traits\UtilsTrait;

class CustomItemAPI extends PluginBase
{
    const LAST_VERSION = "3.2.5";

    use UtilsTrait;
    use DevUtils;

    private ItemManager $manager;
    private static self $instance;

    protected function onLoad(): void
    {
        $this->getServer()->getLogger()->debug("[CustomItemAPI] Logs //: CustomItemAPI starting plugin...");
        $this->saveDefaultConfig();
        $this->manager = new ItemManager($this);
        $this->loadConfigurationFiles();
        self::$instance = $this;
    }

    protected function onEnable(): void
    {
        $this->getServer()->getPluginManager()->registerEvents(new PlayerListeners($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new PacketListeners($this), $this);

        $this->getAPI()->start();
        $this->getServer()->getLogger()->debug("[CustomItemAPI] Logs //: CustomItemAPI has started.");
    }

    protected function onDisable(): void
    {
        $this->getServer()->getLogger()->debug("[CustomItemAPI] Logs //: CustomItemAPI has disable.");
    }

    public static function getInstance(): self { return self::$instance; }

    public function getAPI(): ItemManager {
        return $this->manager;
    }
}
