<?php
namespace Nyrok\LobbyCore\Utils;

use Nyrok\LobbyCore\Core;
use Nyrok\LobbyCore\Managers\LobbyManager;
use pocketmine\player\Player;
use pocketmine\world\Position;

abstract class PlayerUtils
{
    /**
     * @param Player $player
     */
    public static function teleportToSpawn(Player $player): void {
        $player->teleport(LobbyManager::getSpawnPosition());
    }

    /**
     * @param Player $player
     */
    public static function teleportToFFA(Player $player): void {
        $player->teleport(self::getFFAPosition());
    }

    /**
     * @return Position
     */
    private static function getFFAPosition(): Position
    {
        if(!Core::getInstance()->getServer()->getWorldManager()->isWorldLoaded(Core::getInstance()->getConfig()->getNested("ffa.position.world", "world"))) Core::getInstance()->getServer()->getWorldManager()->loadWorld(Core::getInstance()->getConfig()->getNested("ffa.position.world", "world"));
        return new Position((int)Core::getInstance()->getConfig()->getNested("ffa.position.x", 0), (int)Core::getInstance()->getConfig()->getNested("ffa.position.y", 0), (int)Core::getInstance()->getConfig()->getNested("ffa.position.z", 0), Core::getInstance()->getServer()->getWorldManager()->getWorldByName(Core::getInstance()->getConfig()->getNested("ffa.position.world", "world")));
    }

    /**
     * @param Player $player
     */
    public static function bumpPlume(Player $player): void {
        $motion = $player->getMotion();
        $motion->x += (float)Core::getInstance()->getConfig()->getNested("bump.x", 1);
        $motion->y += (float)Core::getInstance()->getConfig()->getNested("bump.y", 1);
        $motion->z += (float)Core::getInstance()->getConfig()->getNested("bump.z", 1);
        $player->setMotion($motion);
    }

}