<?php

declare(strict_types=1);

namespace Nyrok\LobbyCore\Menu\type;

use Nyrok\LobbyCore\Menu\InvMenu;
use Nyrok\LobbyCore\Menu\type\graphic\InvMenuGraphic;
use pocketmine\inventory\Inventory;
use pocketmine\player\Player;

interface InvMenuType{

	public function createGraphic(InvMenu $menu, Player $player) : ?InvMenuGraphic;

	public function createInventory() : Inventory;
}