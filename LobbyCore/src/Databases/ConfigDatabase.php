<?php
namespace Nyrok\LobbyCore\Databases;

use Nyrok\LobbyCore\Core;
use pocketmine\utils\Config;

final class ConfigDatabase extends Config
{
    public function __construct(string $file = "config.yml", int $type = Config::YAML, array $default = [])
    {
        parent::__construct(Core::getInstance()->getDataFolder().$file, $type, $default);
    }

}