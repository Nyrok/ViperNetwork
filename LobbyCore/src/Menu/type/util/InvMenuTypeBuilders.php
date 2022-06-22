<?php

declare(strict_types=1);

namespace Nyrok\LobbyCore\Menu\type\util;

use Nyrok\LobbyCore\Menu\type\util\builder\BlockActorFixedInvMenuTypeBuilder;
use Nyrok\LobbyCore\Menu\type\util\builder\BlockFixedInvMenuTypeBuilder;
use Nyrok\LobbyCore\Menu\type\util\builder\DoublePairableBlockActorFixedInvMenuTypeBuilder;

final class InvMenuTypeBuilders{

	public static function BLOCK_ACTOR_FIXED() : BlockActorFixedInvMenuTypeBuilder{
		return new BlockActorFixedInvMenuTypeBuilder();
	}

	public static function BLOCK_FIXED() : BlockFixedInvMenuTypeBuilder{
		return new BlockFixedInvMenuTypeBuilder();
	}

	public static function DOUBLE_PAIRABLE_BLOCK_ACTOR_FIXED() : DoublePairableBlockActorFixedInvMenuTypeBuilder{
		return new DoublePairableBlockActorFixedInvMenuTypeBuilder();
	}
}