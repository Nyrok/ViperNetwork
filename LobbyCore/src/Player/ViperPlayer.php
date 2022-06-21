<?php /** @noinspection PhpIncompatibleReturnTypeInspection */

namespace Nyrok\LobbyCore\Player;

use Nyrok\LobbyCore\Managers\LanguageManager;
use Nyrok\LobbyCore\Utils\PlayerUtils;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;
use Respect\Validation\Rules\Countable;

final class ViperPlayer extends Player{

    public array $cps = [], $clicksData = [];

    private PlayerProperties $properties;

    private CompoundTag $tag;

    public function initEntity(CompoundTag $nbt): void
    {
        $this->tag = $nbt;
        $this->initPlayerClickData();
        $this->properties = new PlayerProperties($this);
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

        $properties = $this->getPlayerProperties();
        $properties->normalize($properties->getProperties("parameters"));
        foreach ($properties->getProperties("parameters") as $key => $value){
            if (is_numeric($value)){
                $message .= $key . " : " . $value . " ┆ ";
           }
        }
        $this->sendActionBarMessage($message);
    }

    public function saveNBT(): CompoundTag
    {
        $nbt = parent::saveNBT();
        foreach ($this->properties->getPropertiesList() as $property => $value){
            $nbt = PlayerUtils::valueToTag($property, $value, $nbt);
        }
        return $nbt;
    }

    public function onUpdate(int $currentTick): bool
    {
        $properties = $this->getPlayerProperties();
        if (is_numeric($properties->getNestedProperties("parameters.cps")) || $properties->getNestedProperties("parameters.cps") === true){
            $this->properties->setNestedProperties("parameters.cps", $this->getCps());
        }
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

    public function getLanguage(): \Nyrok\LobbyCore\Objects\Language
    {
        return LanguageManager::parseLanguage(parent::getLocale());
    }

    public function removePlayerClickData() : void{
        unset($this->clicksData, $this->cps);
    }
}