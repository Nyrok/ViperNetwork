<?php
namespace Nyrok\LobbyCore\Managers;

use Nyrok\LobbyCore\Core;
use Nyrok\LobbyCore\Player\ViperPlayer;
use Nyrok\LobbyCore\Utils\PlayerUtils;
use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemIds;
use pocketmine\item\VanillaItems;
use pocketmine\player\GameMode;

abstract class HotbarManager
{
    /**
     * @var Item[]
     */
    private static array $items = [];

    public static function initItems(): void {
        $item = VanillaItems::WRITTEN_BOOK();
        $item->setAuthor(Core::getInstance()->getConfig()->getNested("book.author", "ViperNetwork"));
        $item->setTitle(Core::getInstance()->getConfig()->getNested("book.title", "Présentation"));
        foreach(Core::getInstance()->getConfig()->getNested("book.pages", []) as $page => $text){
            $item->setPageText($page - 1, $text);
        }
        $item->setCustomName("Présentation de ViperNetwork");
        self::$items[0] = $item;
        self::$items[2] = VanillaItems::DIAMOND_SWORD()->setCustomName("FFA");
        self::$items[4] = VanillaItems::COMPASS()->setCustomName("Modes de Jeux");
        self::$items[6] = VanillaItems::FEATHER()->setCustomName("Bump");
        self::$items[8] = (new Item(new ItemIdentifier(ItemIds::MINECART_WITH_CHEST, 0), "Paramètres"))->setCustomName("Paramètres");
        foreach (self::getItems() as $item){
            Core::getInstance()->getLogger()->notice("[HOTBAR] Item: ".$item->getName()." Loaded");
        }
    }

    /**
     * @return Item[]
     */
    public static function getItems(): array
    {
        return self::$items;
    }

    public static function load(ViperPlayer $player){
        $player->getEffects()->clear();
        $player->getArmorInventory()->clearAll();
        $player->getInventory()->clearAll();
        $player->setGamemode(GameMode::SURVIVAL());
        $player->getInventory()->setContents(self::getItems());
        PlayerUtils::teleportToSpawn($player);
    }

}