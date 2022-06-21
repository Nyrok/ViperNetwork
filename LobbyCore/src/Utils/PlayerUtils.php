<?php
namespace Nyrok\LobbyCore\Utils;

use Nyrok\LobbyCore\Core;
use Nyrok\LobbyCore\Managers\LobbyManager;
use Nyrok\LobbyCore\Player\ViperPlayer;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\world\Position;

abstract class PlayerUtils
{
    /**
     * @param ViperPlayer $player
     */
    public static function teleportToSpawn(ViperPlayer $player): void {
        $player->teleport(LobbyManager::getSpawnPosition());
    }

    /**
     * @param ViperPlayer $player
     */
    public static function teleportToFFA(ViperPlayer $player): void {
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
     * @param ViperPlayer $player
     */
    public static function bumpPlume(ViperPlayer $player): void {
        $motion = $player->getMotion();
        $vector = $player->getDirectionVector();
        $motion->x += $vector->x * (float)Core::getInstance()->getConfig()->getNested("bump.x", 1.0);
        $motion->y += (float)Core::getInstance()->getConfig()->getNested("bump.y", 1.0);
        $motion->z += $vector->z * (float)Core::getInstance()->getConfig()->getNested("bump.z", 1.0);
        $player->setMotion($motion);
    }

    public static function valueToTag(string $property, mixed $value, ?CompoundTag $nbt = null): CompoundTag{
        if(!$nbt) $nbt = new CompoundTag();
        return match (gettype($value)){
            "integer" => $nbt->setInt($property, $value),
            "double" => $nbt->setDouble($property, $value),
            "string" => $nbt->setString($property, $value),
            "boolean" => $nbt->setByte($property, $value),
            "array" => $nbt->setTag($property, self::arraytoTag($value)),
        };
    }

    public static function arraytoTag(array &$array): CompoundTag {
        $nbt = new CompoundTag();
        foreach($array as $property => $value){
            match (gettype($value)){
                "integer" => $nbt->setInt($property, $value),
                "double" => $nbt->setDouble($property, $value),
                "string" => $nbt->setString($property, $value),
                "boolean" => $nbt->setByte($property, $value),
                "array" => $nbt->setTag($property, self::arrayToTag($value)),
            };
        }
        return $nbt;
    }
}