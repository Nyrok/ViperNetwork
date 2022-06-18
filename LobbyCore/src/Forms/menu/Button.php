<?php

declare(strict_types=1);

namespace Nyrok\LobbyCore\Forms\menu;

use InvalidArgumentException;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Immutable;
use JsonSerializable;

#[Immutable(Immutable::PRIVATE_WRITE_SCOPE)]
class Button implements JsonSerializable{

	public function __construct(public /*readonly*/ string $text, public /*readonly*/ ?Image $image = null, private ?int $value = null){ }

	public function getValue() : int{
		return $this->value ?? throw new InvalidArgumentException("Trying to access an uninitialized value");
	}

	public function setValue(int $value) : self{
		$this->value = $value;
		return $this;
	}

	/** @phpstan-return array<string, mixed> */
	#[ArrayShape(["text" => "string", "image" => "null|\Nyrok\LobbyCore\Forms\menu\Image"])] public function jsonSerialize() : array{
		$ret = ["text" => $this->text];
		if($this->image !== null){
			$ret["image"] = $this->image;
		}

		return $ret;
	}
}
