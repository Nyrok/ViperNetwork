<?php

namespace Nyrok\LobbyCore\Managers;

use Nyrok\LobbyCore\Core;
use Nyrok\LobbyCore\Forms\menu\Button;
use Nyrok\LobbyCore\Forms\MenuForm;
use Nyrok\LobbyCore\Forms\ModalForm;
use Nyrok\LobbyCore\Player\ViperPlayer;
use pocketmine\player\Player;
use pocketmine\world\Position;

abstract class LobbyManager
{

    private static function getX(): array {
        return Core::getInstance()->getConfig()->getNested("areas.spawn.x", [0, 0]);
    }

    private static function getY(): array {
        return Core::getInstance()->getConfig()->getNested("areas.spawn.y", [0, 0]);
    }

    private static function getZ(): array {
        return Core::getInstance()->getConfig()->getNested("areas.spawn.z", [0, 0]);
    }

    private static function getMinX(): int {
        return min(self::getX());
    }

    private static function getMaxX(): int {
        return max(self::getX());
    }

    private static function getMinY(): int {
        return min(self::getY());
    }

    private static function getMaxY(): int {
        return max(self::getY());
    }

    private static function getMinZ(): int {
        return min(self::getZ());
    }

    private static function getMaxZ(): int {
        return max(self::getZ());
    }

    public static function onSpawn(Position $position): bool {
        return ($position->x >= self::getMinX() and $position->x <= self::getMaxX()) and
            ($position->y >= self::getMinY() and $position->y <= self::getMaxY()) and
            ($position->z >= self::getMinZ() and $position->z <= self::getMaxZ());
    }

    public static function getSpawnPosition(): Position
    {
        if(!Core::getInstance()->getServer()->getWorldManager()->isWorldLoaded(Core::getInstance()->getConfig()->getNested("positions.spawn.world", "world"))) Core::getInstance()->getServer()->getWorldManager()->loadWorld(Core::getInstance()->getConfig()->getNested("positions.spawn.world", "world"));
        return new Position((int)Core::getInstance()->getConfig()->getNested("positions.spawn.x", 0), (int)Core::getInstance()->getConfig()->getNested("positions.spawn.y", 0), (int)Core::getInstance()->getConfig()->getNested("positions.spawn.z", 0), Core::getInstance()->getServer()->getWorldManager()->getWorldByName(Core::getInstance()->getConfig()->getNested("positions.spawn.world", "world")));
    }

    public static function load(ViperPlayer $player): void {
        HotbarManager::load($player);
    }

}