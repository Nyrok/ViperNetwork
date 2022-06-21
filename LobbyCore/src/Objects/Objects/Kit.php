<?php

namespace Nyrok\LobbyCore\Objects;

use Nyrok\LobbyCore\Managers\KitsManager;
use pocketmine\inventory\transaction\action\SlotChangeAction;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\player\Player;

final class Kit
{
    public function __construct(private string $name, private ?string $permission = null, private array $contents = [])
    {
        $this->update();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getContents(): array
    {
        return $this->contents;
    }

    /**
     * @param array $contents
     */
    public function setContents(array $contents): void
    {
        $this->contents = $contents;
    }

    /**
     * @return Item[]
     */
    public function getItems(): array {
        return array_filter($this->getContents(), function ($value, $key){
            if($value instanceof Item and !in_array($value->getId(), [47, 48, 50, 51]) and $value->getNamedTag()->getTag('imbougeable')) return true;
            return false;
        });
    }

    /**
     * @return Item[]|null[]
     */
    public function getArmor(): ?array {
        return array_values(array_filter($this->getContents(), function ($value, $key){
            if($value instanceof Item and in_array($value->getId(), [47, 48, 50, 51]) and $value->getNamedTag()->getTag('imbougeable')) return true;
            return false;
        }, ARRAY_FILTER_USE_BOTH));
    }

    /**
     * @return string|null
     */
    public function getPermission(): ?string
    {
        return $this->permission;
    }

    public function send(Player $player){
        $player->getEffects()->clear();
        $player->getArmorInventory()->clearAll();
        $player->getInventory()->clearAll();
        $player->getInventory()->setContents(self::getItems());
        $player->getArmorInventory()->setHelmet(self::getArmor()[0]);
        $player->getArmorInventory()->setChestplate(self::getArmor()[1]);
        $player->getArmorInventory()->setLeggings(self::getArmor()[2]);
        $player->getArmorInventory()->setBoots(self::getArmor()[3]);
    }

    private function update(): void
    {
        $this->setContents(KitsManager::getKitContent($this));
    }

    public function edit(Player $player): void {
        $menu = InvMenu::create(DoubleChestInventory::class);
        $menu->readonly(false);
        $menu->setName(LangUtils::getMessage("editkit-items-title", true, ["{NAME}" => $kit->getName()]));
        $menu->setInventoryCloseListener(function (Player $player, BaseFakeInventory $inventory) use ($kit) {

        });
        $menu->setListener(function (Player $player, Item $itemClicked, Item $itemClickedWith, SlotChangeAction $action) {
            if($itemClicked->getNamedTag()->getTag("inbougeable")) {
                return false;
            }
            return true;
        });
        $menu->getInventory()->setContents($kit->getItems());
        for($i = 36; $i < 54; $i++) {
            switch($i) {
                case 42:
                    $item = ItemFactory::get(Item::STAINED_GLASS, 14, 1);
                    $item->setCustomName(LangUtils::getMessage("editkit-items-lockedname"));
                    $item->setNamedTagEntry(new ByteTag("immovable", 1));
                    $item->setLore(["Casque"]);
                    $menu->getInventory()->setItem($i, $item);
                    break;
                case 41:
                    $item = ItemFactory::get(Item::STAINED_GLASS, 14, 1);
                    $item->setCustomName(LangUtils::getMessage("editkit-items-lockedname"));
                    $item->setNamedTagEntry(new ByteTag("immovable", 1));
                    $item->setLore(["Plastron"]);
                    $menu->getInventory()->setItem($i, $item);
                    break;
                case 39:
                    $item = ItemFactory::get(Item::STAINED_GLASS, 14, 1);
                    $item->setCustomName(LangUtils::getMessage("editkit-items-lockedname"));
                    $item->setNamedTagEntry(new ByteTag("immovable", 1));
                    $item->setLore(["Jambières"]);
                    $menu->getInventory()->setItem($i, $item);
                    break;
                case 38:
                    $item = ItemFactory::get(Item::STAINED_GLASS, 14, 1);
                    $item->setCustomName("§dBloqué");
                    $item->setNamedTagEntry(new ByteTag("immovable", 1));
                    $item->setLore(["Bottes"]);
                    $menu->getInventory()->setItem($i, $item);
                    break;
                case 51:
                    $menu->getInventory()->setItem($i, $kit->getArmor()[0] ?? ItemFactory::get(Item::AIR));
                    break;
                case 50:
                    $menu->getInventory()->setItem($i, $kit->getArmor()[1] ?? ItemFactory::get(Item::AIR));
                    break;
                case 48:
                    $menu->getInventory()->setItem($i, $kit->getArmor()[2] ?? ItemFactory::get(Item::AIR));
                    break;
                case 47:
                    $menu->getInventory()->setItem($i, $kit->getArmor()[3] ?? ItemFactory::get(Item::AIR));
                    break;
                default:
                    $item = ItemFactory::get(Item::STAINED_GLASS, 14, 1);
                    $item->setCustomName("§dBloqué");
                    $item->setNamedTagEntry(new ByteTag("immovable", 1));
                    $menu->getInventory()->setItem($i, $item);
                    break;
            }
        }

        $menu->send($player);
    }
}