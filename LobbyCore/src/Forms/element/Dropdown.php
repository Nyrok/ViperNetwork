<?php

declare(strict_types=1);

namespace Nyrok\LobbyCore\Forms\element;

use JetBrains\PhpStorm\ArrayShape;

/** @phpstan-extends BaseSelector<int> */
class Dropdown extends BaseSelector{

	protected function getType() : string{ return "dropdown"; }

	#[ArrayShape(["options" => "string[]", "default" => "int"])] protected function serializeElementData() : array{
		return [
			"options" => $this->options,
			"default" => $this->default,
		];
	}
}
