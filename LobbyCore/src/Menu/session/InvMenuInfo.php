<?php

declare(strict_types=1);

namespace Nyrok\LobbyCore\Menu\session;

use Nyrok\LobbyCore\Menu\InvMenu;
use Nyrok\LobbyCore\Menu\type\graphic\InvMenuGraphic;

final class InvMenuInfo{

	public function __construct(
		public InvMenu $menu,
		public InvMenuGraphic $graphic
	){}
}