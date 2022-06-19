<?php

namespace Nyrok\LobbyCore\Traits;

use InvalidArgumentException;

trait PropertiesTrait
{

    public array $properties = [];

    public function getProperties(string $name): mixed{
        return $this->properties[strtolower($name)] ?? null;
    }

    public function setProperties(string $name, $value): self{
        $this->properties[strtolower($name)] = $value;
        return $this;
    }

    public function removeProperties(string $name): self{
        unset($this->properties[$name]);
        return $this;
    }

    public function getPropertiesList(): array{
        return $this->properties;
    }

    public function getNestedProperties(string $name, $array = null): mixed{
        $ex = explode(".", $name);
        for ($count = count($ex); $count > 0; $count--) {
            try {
                $array = $array[$ex[$count - 1]];
                var_dump($array);
            } catch (InvalidArgumentException $e) {
                $this->getNestedProperties($name, $array);
            }
        }
        return $array;
    }

    public function setBaseProperties(array $properties): void{
        $this->properties = $properties;
    }
}