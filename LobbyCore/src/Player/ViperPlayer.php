<?php /** @noinspection PhpIncompatibleReturnTypeInspection */

namespace Nyrok\LobbyCore\Player;

use Nyrok\LobbyCore\Managers\GradesManager;
use Nyrok\LobbyCore\Managers\LanguageManager;
use Nyrok\LobbyCore\Objects\Grade;
use Nyrok\LobbyCore\Objects\Language;
use Nyrok\LobbyCore\Utils\PlayerUtils;
use pocketmine\item\PotionType;
use pocketmine\item\SplashPotion;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;
use pocketmine\world\Position;

final class ViperPlayer extends Player{

    public array $cps = [], $clicksData = [];

    private PlayerProperties $properties;

    private CompoundTag $tag;

    private int $potioncount = 0;

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
        $this->setScoreTag(str_replace(["{player}"], [$this->getName()], $this->getGrade()->getScoretag()));
        $this->setNameTag(str_replace(["{player}"], [$this->getName()], $this->getGrade()->getNametag()));
        $content = $this->getInventory()->getContents();
        $properties = $this->getPlayerProperties();
        if ($properties->canSend("parameters.cps", true)){
            $properties->setNestedProperties("parameters.cps", $this->getCps());
        }
        if($properties->canSend("parameters.ping", true)){
            $properties->setNestedProperties("parameters.ping", $this->getNetworkSession()->getPing());
        }
        if($properties->canSend("parameters.pots", true)){
            array_walk($content, function ($value){
                if($value instanceof SplashPotion && ($value->getType() === PotionType::HEALING() || $value->getType()=== PotionType::STRONG_HEALING())) {
                    $this->potioncount++;
                }
            });
            $properties->setNestedProperties("parameters.pots", $this->potioncount);
        }
        $this->sendParameters();
        $this->potioncount = 0;
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

    public function getLanguage(): Language
    {
        return LanguageManager::parseLanguage(parent::getLocale());
    }

    public function getGrade(): Grade
    {
        return GradesManager::parseGrade($this->getPlayerProperties()->getNestedProperties("infos.grade")) ?? GradesManager::getDefaultGrade();
    }

    public function removePlayerClickData() : void{
        unset($this->clicksData, $this->cps);
    }

    public function knockBack(float $x, float $z, float $force = 0.4, ?float $verticalLimit = 0.4): void{
        $f = sqrt($x * $x + $z * $z);
        if($f <= 0){
            return;
        }
        if(mt_rand() / mt_getrandmax() > $this->knockbackResistanceAttr->getValue()){
            $f = 1 / $f;

            $motionX = $this->motion->x / 2;
            $motionY = $this->motion->y / 2;
            $motionZ = $this->motion->z / 2;
            $motionX += $x * $f * $force;
            $motionY += $force;
            $motionZ += $z * $f * $force;

            $verticalLimit ??= $force;
            if($motionY > $verticalLimit){
                $motionY = $verticalLimit;
            }

            $this->setMotion(new Vector3($motionX, $motionY, $motionZ));
        }
    }

    public function updatePermissions(){
        $this->setBasePermission("", "");
    }

    public function smoothPerle(Position $position): void {
        $this->move($position->getX() - $this->getPosition()->getX(), $position->getY(), $position->getZ() - $this->getPosition()->getZ());
    }
}