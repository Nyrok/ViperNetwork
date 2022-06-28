<?php

namespace Nyrok\LobbyCore\Databases;

use Nyrok\LobbyCore\Core;
use pocketmine\utils\Config;

final class SanctionsDatabase extends Config
{
    public function __construct(string $file = "sanctions.json", int $type = Config::JSON, array $default = [])
    {
        parent::__construct(Core::getInstance()->getDataFolder().$file, $type, $default);
    }
}