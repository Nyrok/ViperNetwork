<?php

/*
 *    _______           _______ _________ _______  _______ __________________ _______  _______  _______  _______ _________
 *   (  ____ \|\     /|(  ____ \\__   __/(  ___  )(       )\__   __/\__   __/(  ____ \(       )(  ___  )(  ____ )\__   __/
 *   | (    \/| )   ( || (    \/   ) (   | (   ) || () () |   ) (      ) (   | (    \/| () () || (   ) || (    )|   ) (
 *   | |      | |   | || (_____    | |   | |   | || || || |   | |      | |   | (__    | || || || (___) || (____)|   | |
 *   | |      | |   | |(_____  )   | |   | |   | || |(_)| |   | |      | |   |  __)   | |(_)| ||  ___  ||  _____)   | |
 *   | |      | |   | |      ) |   | |   | |   | || |   | |   | |      | |   | (      | |   | || (   ) || (         | |
 *   | (____/\| (___) |/\____) |   | |   | (___) || )   ( |___) (___   | |   | (____/\| )   ( || )   ( || )      ___) (___
 *   (_______/(_______)\_______)   )_(   (_______)|/     \|\_______/   )_(   (_______/|/     \||/     \||/       \_______/
 *
 *   This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU Lesser General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   By: refaltor
 *   Discord: Refaltor#6969
 */

declare(strict_types=1);


namespace Nyrok\LobbyCore\Librairies\refaltor\customitemapi\managers;

use Exception;
use pocketmine\inventory\CreativeInventory;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\StringToItemParser;
use pocketmine\network\mcpe\convert\GlobalItemTypeDictionary;
use pocketmine\network\mcpe\convert\ItemTranslator;
use pocketmine\network\mcpe\protocol\ItemComponentPacket;
use pocketmine\network\mcpe\protocol\serializer\ItemTypeDictionary;
use pocketmine\network\mcpe\protocol\types\CacheableNbt;
use pocketmine\network\mcpe\protocol\types\ItemComponentPacketEntry;
use pocketmine\network\mcpe\protocol\types\ItemTypeEntry;
use Nyrok\LobbyCore\Librairies\refaltor\customitemapi\CustomItemAPI;
use Nyrok\LobbyCore\Librairies\refaltor\customitemapi\items\BaseItem;
use Nyrok\LobbyCore\Librairies\refaltor\customitemapi\items\CustomArmor;
use Nyrok\LobbyCore\Librairies\refaltor\customitemapi\items\CustomAxe;
use Nyrok\LobbyCore\Librairies\refaltor\customitemapi\items\CustomFood;
use Nyrok\LobbyCore\Librairies\refaltor\customitemapi\items\CustomHoe;
use Nyrok\LobbyCore\Librairies\refaltor\customitemapi\items\CustomPickaxe;
use Nyrok\LobbyCore\Librairies\refaltor\customitemapi\items\CustomPotion;
use Nyrok\LobbyCore\Librairies\refaltor\customitemapi\items\CustomShovel;
use Nyrok\LobbyCore\Librairies\refaltor\customitemapi\items\CustomSword;
use Nyrok\LobbyCore\Librairies\refaltor\customitemapi\traits\OwnedTrait;
use ReflectionClass;
use Webmozart\PathUtil\Path;
use const pocketmine\BEDROCK_DATA_PATH;

class ItemManager
{
    use OwnedTrait;

    public function __construct(CustomItemAPI $plugin)
    {
        $this->setPlugin($plugin);
    }

    public ?ItemComponentPacket $packet = null;
    protected array $registered = [];
    protected array $packetEntries = [];
    public array $items = [];


    public function start(): void {
        CreativeInventory::getInstance()->clear();
        $ref = new ReflectionClass(ItemTranslator::class);
        $coreToNetMap = $ref->getProperty("simpleCoreToNetMapping");
        $netToCoreMap = $ref->getProperty("simpleNetToCoreMapping");
        $coreToNetMap->setAccessible(true);
        $netToCoreMap->setAccessible(true);
        $coreToNetValues = $coreToNetMap->getValue(ItemTranslator::getInstance());
        $netToCoreValues = $netToCoreMap->getValue(ItemTranslator::getInstance());
        $ref_1 = new ReflectionClass(ItemTypeDictionary::class);
        $itemTypeMap = $ref_1->getProperty("itemTypes");
        $itemTypeMap->setAccessible(true);
        $itemTypeEntries = $itemTypeMap->getValue(GlobalItemTypeDictionary::getInstance()->getDictionary());
        $this->packetEntries = [];
        foreach ($this->getItemInCache() as $item) {
            $runtimeId = $item->getId() + ($item->getId() > 0 ? 5000 : -5000);
            $coreToNetValues[$item->getId()] = $runtimeId;
            $netToCoreValues[$runtimeId] = $item->getId();
            $itemTypeEntries[] = new ItemTypeEntry("custom:" . $item->getName(), $runtimeId, true);
            $this->packetEntries[] = new ItemComponentPacketEntry("custom:" . $item->getName(), new CacheableNbt($item->getComponents()));
            $this->registered[] = $item;
            $new = clone $item;
            StringToItemParser::getInstance()->register($item->getName() . ':custom', fn() => $new);
            ItemFactory::getInstance()->register($item, true);
            CreativeInventory::getInstance()->add($item);
            $netToCoreMap->setValue(ItemTranslator::getInstance(), $netToCoreValues);
            $coreToNetMap->setValue(ItemTranslator::getInstance(), $coreToNetValues);
            $itemTypeMap->setValue(GlobalItemTypeDictionary::getInstance()->getDictionary(), $itemTypeEntries);
            $this->packet = ItemComponentPacket::create($this->packetEntries);
        }

        $creativeItems = json_decode(file_get_contents(Path::join(BEDROCK_DATA_PATH, "creativeitems.json")), true);
        foreach($creativeItems as $data){
            $item = Item::jsonDeserialize($data);
            if($item->getName() === "Unknown"){
                continue;
            }
            CreativeInventory::getInstance()->add($item);
        }
    }


    public function register(BaseItem|CustomArmor|CustomFood|CustomPickaxe|CustomAxe|CustomShovel|CustomSword|CustomHoe|CustomPotion $item) {
        try {
            $this->items[] = $item;
        } catch (Exception $exception) {
            $this->getPlugin()->getLogger()->error("[!] ". $item::class ." Is not custom item.");
        }
    }

    public function registerAll(array $items) {
        foreach ($items as $item) {
            try {
                if ($item instanceof BaseItem ||
                    $item instanceof CustomArmor ||
                    $item instanceof CustomFood ||
                    $item instanceof CustomShovel ||
                    $item instanceof CustomAxe ||
                    $item instanceof CustomPickaxe ||
                    $item instanceof CustomSword ||
                    $item instanceof CustomHoe ||
                    $item instanceof CustomPotion
                ) {
                    $this->items[] = $item;
                } else $this->getPlugin()->getServer()->getLogger()->error("[!] ". $item::class ." Is not custom item.");
            } catch (Exception $exception) {
                $this->getPlugin()->getServer()->getLogger()->error("[!] ". $item::class ." Is not custom item.");
            }
        }
    }

    public function getPacket(): ?ItemComponentPacket {
        return $this->packet;
    }


    public function getItemInCache(): array {
        return $this->items;
    }
}