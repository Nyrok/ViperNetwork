<?php

declare(strict_types=1);

namespace Nyrok\LobbyCore\Menu\type\util\builder;

use Nyrok\LobbyCore\Menu\type\InvMenuType;

interface InvMenuTypeBuilder{

	public function build() : InvMenuType;
}