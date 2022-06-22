<?php

declare(strict_types=1);

namespace Nyrok\LobbyCore\Menu\session\network\handler;

use Closure;
use Nyrok\LobbyCore\Menu\session\network\NetworkStackLatencyEntry;

interface PlayerNetworkHandler{

	public function createNetworkStackLatencyEntry(Closure $then) : NetworkStackLatencyEntry;
}