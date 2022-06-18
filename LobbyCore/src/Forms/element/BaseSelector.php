<?php

declare(strict_types=1);

namespace Nyrok\LobbyCore\Forms\element;

use JetBrains\PhpStorm\Immutable;
use JetBrains\PhpStorm\Pure;
use pocketmine\form\FormValidationException;
use function gettype;
use function is_int;

/**
 * @phpstan-template TValue
 * @phpstan-extends BaseElementWithValue<TValue>
 */
abstract class BaseSelector extends BaseElementWithValue{

	/** @phpstan-param list<string> $options */
	#[Pure] public function __construct(
		string $text,
		#[Immutable] public /*readonly*/ array $options,
		#[Immutable] public /*readonly*/ int $default = 0,
	){
		parent::__construct($text);
	}

	public function getSelectedOption() : string{
		return $this->options[$this->getValue()];
	}

	protected function validateValue(mixed $value) : void{
		if(!is_int($value)){
			throw new FormValidationException("Expected int, got " . gettype($value));
		}
		if(!isset($this->options[$value])){
			throw new FormValidationException("Option $value does not exist");
		}
	}
}
