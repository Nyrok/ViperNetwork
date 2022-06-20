<?php
namespace Nyrok\LobbyCore\Commands;

use Nyrok\LobbyCore\Core;
use Nyrok\LobbyCore\Traits\CommandTrait;
use pocketmine\command\Command;
use pocketmine\lang\Translatable;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;

abstract class ViperCommands extends Command implements PluginOwned
{
    use CommandTrait;

    public function __construct(string $name, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
        self::setCommand($this);
        self::init();
    }

    public function getOwningPlugin(): Plugin
    {
        return Core::getInstance();
    }
}