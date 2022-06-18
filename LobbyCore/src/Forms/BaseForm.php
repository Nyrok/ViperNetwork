<?php

declare(strict_types=1);

namespace Nyrok\LobbyCore\Forms;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Immutable;
use pocketmine\form\Form;

#[Immutable]
abstract class BaseForm implements Form{

	public function __construct(public string $title){ }

	abstract protected function getType() : string;

	/** @phpstan-return array<string, mixed> */
	abstract protected function serializeFormData() : array;

	/** @phpstan-return array<string, mixed> */
	#[ArrayShape(["buttons" => "\forms\menu\Button[]", "content" => "string", "title" => "string", "type" => "string"])] final public function jsonSerialize() : array{
		$ret = $this->serializeFormData();
		$ret["type"] = $this->getType();
		$ret["title"] = $this->title;

		return $ret;
	}
}
