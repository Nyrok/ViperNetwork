<?php

namespace Nyrok\LobbyCore\Objects;

use JsonException;
use Nyrok\LobbyCore\Core;
use Nyrok\LobbyCore\Managers\KitsManager;
use Nyrok\LobbyCore\Menu\inventory\InvMenuInventory;
use Nyrok\LobbyCore\Menu\transaction\InvMenuTransactionResult;
use Nyrok\LobbyCore\Menu\InvMenu;
use Nyrok\LobbyCore\Menu\transaction\SimpleInvMenuTransaction;
use Nyrok\LobbyCore\Menu\type\InvMenuTypeIds;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\player\Player;

final class Kit
{
    /**
     * @throws JsonException
     */
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
     * @throws JsonException
     */
    public function setContents(array $contents): void
    {
        Core::getInstance()->getKits()->set($this->getName(), $contents);
        Core::getInstance()->getKits()->save();
        $this->contents = $contents;
    }

    /**
     * @return Item[]
     */
    public function getItems(): array {
        return array_filter($this->getContents(), function ($value){
            if($value instanceof Item and !in_array($value->getId(), [47, 48, 50, 51]) and $value->getNamedTag()->getTag('imbougeable')) return true;
            return false;
        });
    }

    /**
     * @return Item[]|null[]
     */
    public function getArmor(): ?array {
        return array_values(array_filter($this->getContents(), function ($value){
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

    /**
     * @throws JsonException
     */
    private function update(): void
    {
        $this->setContents(KitsManager::getKitContent($this));
    }

    public function edit(Player $player): void {
        $menu = InvMenu::create(InvMenuTypeIds::TYPE_DOUBLE_CHEST);
        $menu->setName($this->getName());
        $menu->setInventoryCloseListener(function (Player $player, InvMenuInventory $inventory){
            $this->setContents(array_filter($inventory->getContents(), function ($item){
                if($item->getNamedTag()->getTag("imbougeable")) return false;
                return true;
            }));
        });
        $menu->setListener(function (SimpleInvMenuTransaction $invMenuTransaction) {
            if($invMenuTransaction->getItemClicked()->getNamedTag()->getTag("imbougeable")) {
                return new InvMenuTransactionResult(true);
            }
            return new InvMenuTransactionResult(false);
        });
        $menu->getInventory()->setContents($this->getContents());
        for($i = 36; $i < 54; $i++) {
            $item = ItemFactory::getInstance()->get(ItemIds::STAINED_GLASS_PANE, 10, 1);
            $item->setCustomName("§dBloqué");
            $item->getNamedTag()->setTag("imbougeable", new ByteTag(1));
            switch($i) {
                case 42:
                    $item->setLore(["Bottes"]);
                    break;
                case 41:
                    $item->setLore(["Jambières"]);
                    break;
                case 39:
                    $item->setLore(["Plastron"]);
                    break;
                case 38:
                    $item->setLore(["Casque"]);
                    break;
                case 47:
                case 48:
                case 50:
                case 51:
                    continue 2;
            }
            $menu->getInventory()->setItem($i, $item);
        }
        $menu->send($player);
    }
}