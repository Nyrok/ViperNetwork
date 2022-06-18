<?php

declare(strict_types=1);

namespace Nyrok\LobbyCore\Forms\element;

use JetBrains\PhpStorm\ArrayShape;

/** @phpstan-extends BaseSelector<int> */
class StepSlider extends BaseSelector{

	protected function getType() : string{ return "step_slider"; }

	#[ArrayShape(["steps" => "string[]", "default" => "int"])] protected function serializeElementData() : array{
		return [
			"steps" => $this->options,
			"default" => $this->default,
		];
	}
}
