<?php

declare(strict_types=1);

namespace Nyrok\LobbyCore\Menu\type\graphic\network;

use Nyrok\LobbyCore\Menu\session\InvMenuInfo;
use Nyrok\LobbyCore\Menu\session\PlayerSession;
use pocketmine\network\mcpe\protocol\ContainerOpenPacket;

interface InvMenuGraphicNetworkTranslator{

	public function translate(PlayerSession $session, InvMenuInfo $current, ContainerOpenPacket $packet) : void;
}