<?php
namespace Nyrok\LobbyCore;

use Nyrok\LobbyCore\Databases\ConfigDatabase;
use Nyrok\LobbyCore\Managers\CustomItemManager;
use Nyrok\LobbyCore\Managers\HotbarManager;
use Nyrok\LobbyCore\Managers\ListenersManager;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;

class Core extends PluginBase
{
    use SingletonTrait;

    private ConfigDatabase $config;

    protected function onEnable(): void
    {
        $this::setInstance($this);
        $this->saveResource("config.yml", true);

        $this->config = new ConfigDatabase();

        ListenersManager::initListeners($this);
        HotbarManager::initItems();
        CustomItemManager::initCustomItems();

        $this->getLogger()->warning("[LobbyCore] has been enabled!");
    }


    public function getConfig(): ConfigDatabase
    {
        return $this->config;
    }

}