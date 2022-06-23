<?php

namespace Nyrok\LobbyCore\Managers;

use JsonException;
use Nyrok\LobbyCore\Core;
use Nyrok\LobbyCore\Objects\Kit;
use pocketmine\item\Item;

abstract class KitsManager
{
    /**
     * @var Kit[]
     */
    public static array $kits = [];

    /**
     * @throws JsonException
     */
    public static function initKits(): void {
        foreach (self::getConfigKits() as $kit => $permission){
            self::$kits[$kit] = new Kit($kit, $permission);
            Core::getInstance()->getLogger()->notice("[KITS] Kit: $kit with $permission permission Loaded");
        }
    }

    /**
     * @return Kit[]
     */
    public static function getKits(): array
    {
        return self::$kits;
    }

    /**
     * @return array
     */
    private static function getConfigKits(): array {
        return Core::getInstance()->getConfig()->get('kits', []);
    }

    /**
     * @param Kit $kit
     * @return array
     */
    public static function getKitContent(Kit $kit): array
    {
        $content = [];
        foreach(Core::getInstance()->getKits()->get($kit->getName(), []) as $slot => $item){
            $content[$slot] = Item::jsonDeserialize($item);
        }
        return $content;
    }

    public static function getKit(string $kit): ?Kit
    {
        return self::getKits()[$kit] ?? reset(self::$kits);
    }

}