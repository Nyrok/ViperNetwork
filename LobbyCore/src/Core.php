<?php
namespace Nyrok\LobbyCore;

use Nyrok\LobbyCore\Databases\ConfigDatabase;
use Nyrok\LobbyCore\Databases\KitsDatabase;
use Nyrok\LobbyCore\Managers\CommandsManager;
use Nyrok\LobbyCore\Managers\CooldownManager;
use Nyrok\LobbyCore\Managers\CustomItemManager;
use Nyrok\LobbyCore\Managers\FFAManager;
use Nyrok\LobbyCore\Managers\HotbarManager;
use Nyrok\LobbyCore\Managers\KitsManager;
use Nyrok\LobbyCore\Managers\KnockBackManager;
use Nyrok\LobbyCore\Managers\LanguageManager;
use Nyrok\LobbyCore\Managers\ListenersManager;
use Nyrok\LobbyCore\Managers\LobbyManager;
use Nyrok\LobbyCore\Managers\MenuManager;
use pocketmine\entity\Location;
use pocketmine\entity\Zombie;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;

class Core extends PluginBase
{
    use SingletonTrait;

    private ConfigDatabase $config;
    private KitsDatabase $kits;

    protected function onLoad(): void
    {
        CustomItemManager::initCustomItems();
    }

    /**
     * @throws \JsonException
     */
    protected function onEnable(): void
    {
        $this::setInstance($this);
        $this->saveResource("config.yml", true);

        $this->config = new ConfigDatabase();
        $this->kits = new KitsDatabase();

        ListenersManager::initListeners($this);
        HotbarManager::initItems();
        CustomItemManager::registerItems();
        CommandsManager::initCommands();
        LanguageManager::initLanguages();
        CooldownManager::initCooldowns();
        KitsManager::initKits();
        MenuManager::initMenu();
        KnockBackManager::initKnockBack();
        FFAManager::initFFA();

        $this->getLogger()->warning("[LobbyCore] has been enabled!");
    }


    /**
     * @return ConfigDatabase
     */
    public function getConfig(): ConfigDatabase
    {
        return $this->config;
    }

    /**
     * @return KitsDatabase
     */
    public function getKits(): KitsDatabase
    {
        return $this->kits;
    }
}