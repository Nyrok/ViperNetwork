<?php

namespace Nyrok\LobbyCore\Player;

use pocketmine\lang\Translatable;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\player\Player;

class ViperPlayer extends Player{

    public array $cps = [], $clicksData = [];

    private PlayerProperties $properties;

    private CompoundTag $tag;

    public function initEntity(CompoundTag $nbt): void
    {
        $this->tag = $nbt;
        $this->initPlayerClickData();
        $this->properties = new PlayerProperties();
        parent::initEntity($nbt);
    }

    public function getNBT(): CompoundTag{
        return $this->tag;
    }

    public function getPlayerProperties(): PlayerProperties{
        return $this->properties;
    }

    public function sendParameters(): void{
        $message = "";
       foreach ($this->properties->getPropertiesList()["parameters"] as $key => $value){
           $message .= $key . ":" . $value;
       }
       $this->sendPopup($message);
    }

    public function disconnect(string $reason, Translatable|string|null $quitMessage = null): void
    {
        $nbt = $this->getNBT();
        foreach ($this->properties->getPropertiesList() as $property => $value){
            match (gettype($value)){
                "integer" => $nbt->setInt($property, $value),
                "double" => $nbt->setDouble($property, $value),
                "string" => $nbt->setString($property, $value),
                "boolean" => $nbt->setByte($property, $value),
                "array" => $nbt->setTag($property, new ListTag($value)),
            };
        }
        parent::disconnect($reason, $quitMessage);
    }

    public function onUpdate(int $currentTick): bool
    {
        $this->properties->setNestedProperties("parameters.cps", $this->getCps());
        $this->sendParameters();
        return parent::onUpdate($currentTick);
    }


    public function initPlayerClickData() : void{
       $this->clicksData = [];
        $this->cps = [];
    }

    public function addClick() : void{
        array_unshift($this->clicksData, microtime(true));
        if(count($this->clicksData) >= 100){
            array_pop($this->clicksData);
        }
    }

    public function getCps( float $deltaTime = 1.0, int $roundPrecision = 1) : float{
        if(empty($this->clicksData)){
            return 0.0;
        }
        $ct = microtime(true);
        return round(count(array_filter($this->clicksData, static function(float $t) use ($deltaTime, $ct) : bool{
                return ($ct - $t) <= $deltaTime;
            })) / $deltaTime, $roundPrecision);
    }

    public function removePlayerClickData() : void{
        unset($this->clicksData, $this->cps);
    }

}