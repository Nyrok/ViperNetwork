<?php

namespace Nyrok\LobbyCore\Managers;

use Nyrok\LobbyCore\Core;
use Nyrok\LobbyCore\Objects\FFA;

abstract class FFAManager
{
    private static ?FFA $ffa = null;

    public static function initFFA(): void {
        self::$ffa = new FFA(
            KitsManager::getKit(Core::getInstance()->getConfig()->getNested("ffa.kit", "ffa")),
            [
                Core::getInstance()->getConfig()->getNested("ffa.area.x", [0, 0]),
                Core::getInstance()->getConfig()->getNested("ffa.area.y", [0, 0]),
                Core::getInstance()->getConfig()->getNested("ffa.area.z", [0, 0]),
            ]
        );
        Core::getInstance()->getLogger()->notice("[FFA] Successfully Loaded");
    }

    /**
     * @return FFA|null
     */
    public static function getFFA(): ?FFA
    {
        return self::$ffa;
    }

    /**
     * @param FFA|null $ffa
     */
    public static function setFFA(?FFA $ffa): void
    {
        self::$ffa = $ffa;
    }
}