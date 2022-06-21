<?php
namespace Nyrok\LobbyCore\Items;

use Nyrok\LobbyCore\Objects\CustomFood;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\entity\Living;

final class CookieForce extends CustomFood
{
    const NAME = "Cookie de Force";
    const ID = 1000;

    public function onConsume(Living $consumer): void
    {
        $consumer->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), (8*60)*20, 0, true));
        parent::onConsume($consumer);
    }
}