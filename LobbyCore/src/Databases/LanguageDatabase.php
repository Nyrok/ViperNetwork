<?php

namespace Nyrok\LobbyCore\Databases;

use Nyrok\LobbyCore\Core;
use pocketmine\utils\Config;

class LanguageDatabase extends Config
{
    public function __construct(string $file, int $type = Config::YAML, array $default = [])
    {
        parent::__construct(Core::getInstance()->getDataFolder().$file.".yml", $type, $default);
    }

}