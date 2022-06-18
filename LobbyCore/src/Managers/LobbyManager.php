<?php

namespace Nyrok\LobbyCore\Managers;

use Nyrok\LobbyCore\Core;
use Nyrok\LobbyCore\Forms\SimpleForm;
use pocketmine\world\Position;
use pocketmine\player\Player;

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

    public static function modesForm(Player $player): void
    {
        $form = new SimpleForm(function (Player $player, mixed $data): void {
            if ($data === null) return;
            $mode = Core::getInstance()->getConfig()->getNested("modes.$data", []);
            self::confirmModeForm($player, $data, $mode);

        });
        $form->setTitle("Modes de Jeux");
        $form->setContent("Choisissez votre mode de jeu"); // UI Description
        foreach (Core::getInstance()->getConfig()->getNested("modes", []) as $name => $data) {
            $form->addButton($name, -1, "", $name);
        }
        $player->sendForm($form);
    }

    private static function confirmModeForm(Player $player, string $name, array $mode){
        $form = new SimpleForm(function (Player $player, mixed $data) use ($mode): void {
            if ($data === null) return;
            match ($data){
                0 => $player->transfer($mode['ip'], $mode['port'], "Transfère"),
                1 => self::modesForm($player)
            };
        });
        $form->setTitle($name);
        $form->setContent($mode['motd']);
        $form->addButton("Confirmer");
        $form->addButton("Retour");
        $player->sendForm($form);
    }

    public static function getSpawnPosition(): Position
    {
        if(!Core::getInstance()->getServer()->getWorldManager()->isWorldLoaded(Core::getInstance()->getConfig()->getNested("positions.spawn.world", "world"))) Core::getInstance()->getServer()->getWorldManager()->loadWorld(Core::getInstance()->getConfig()->getNested("positions.spawn.world", "world"));
        return new Position((int)Core::getInstance()->getConfig()->getNested("positions.spawn.x", 0), (int)Core::getInstance()->getConfig()->getNested("positions.spawn.y", 0), (int)Core::getInstance()->getConfig()->getNested("positions.spawn.z", 0), Core::getInstance()->getServer()->getWorldManager()->getWorldByName(Core::getInstance()->getConfig()->getNested("positions.spawn.world", "world")));
    }

}