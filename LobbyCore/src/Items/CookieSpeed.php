<?php

use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\entity\Living;
use pocketmine\item\Food;

final class CookieSpeed extends Food
{
    public function onConsume(Living $consumer): void
    {
        $consumer->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), (8*60)*20, 0, false));
        parent::onConsume($consumer);
    }

    public function getFoodRestore(): int
    {
        return 0;
    }

    public function getSaturationRestore(): float
    {
        return 0;
    }
}