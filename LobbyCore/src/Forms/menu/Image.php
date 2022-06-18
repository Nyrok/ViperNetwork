<?php

declare(strict_types=1);

namespace Nyrok\LobbyCore\Forms\menu;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Immutable;
use JetBrains\PhpStorm\Pure;
use JsonSerializable;

#[Immutable]
class Image implements JsonSerializable{

	private function __construct(public /*readonly*/ string $data, public /*readonly*/ string $type){ }

	#[Pure] public static function url(string $data) : self{ return new self($data, "url"); }

	#[Pure] public static function path(string $data) : self{ return new self($data, "path"); }

	/** @phpstan-return array<string, mixed> */
	#[ArrayShape(["type" => "string", "data" => "string"])] public function jsonSerialize() : array{
		return [
			"type" => $this->type,
			"data" => $this->data,
		];
	}
}
