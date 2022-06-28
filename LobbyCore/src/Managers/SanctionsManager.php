<?php

namespace Nyrok\LobbyCore\Managers;

use Nyrok\LobbyCore\Core;
use Nyrok\LobbyCore\Databases\SanctionsDatabase;

abstract class SanctionsManager
{
    public static function getRecidive(string $name, string $reason): SanctionsDatabase {
        return Core::getInstance()->getSanctions()->getNested($name.".$reason", 0);
    }

}