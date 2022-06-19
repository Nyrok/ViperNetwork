<?php

namespace Nyrok\LobbyCore\Trait;

trait PropertiesTrait
{

    private array $properties = [];

    public function getProperties(string $name): mixed{
        return $this->properties[strtolower($name)] ?? null;
    }

    public function setProperties(string $name, $value): self{
        $this->properties[strtolower($name)] = $value;
        return $this;
    }

    public function setNestedProperties($key, $value) : void{
        $vars = explode(".", $key);
        $base = array_shift($vars);

        if(!isset($this->properties[$base])){
            $this->properties[$base] = [];
        }

        $base = &$this->properties[$base];

        while(count($vars) > 0){
            $baseKey = array_shift($vars);
            if(!isset($base[$baseKey])){
                $base[$baseKey] = [];
            }
            $base = &$base[$baseKey];
        }
        $base = $value;
    }
    public function removeProperties(string $name): self{
        unset($this->properties[$name]);
        return $this;
    }

    public function getPropertiesList(): array{
        return $this->properties;
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function getNestedProperties(string $name): mixed{
        $vars = explode(".", $name);
        $base = array_shift($vars);
        if(isset($this->properties[$base])){
            $base = $this->properties[$base];
        }else{
            return null;
        }
        while(count($vars) > 0){
            $baseKey = array_shift($vars);
            if(is_array($base) && isset($base[$baseKey])){
                return $base[$baseKey];
            }else{
                return null;
            }
        }

        return null;
    }

    public function setBaseProperties(array $properties): void{
        $this->properties = $properties;
    }
}