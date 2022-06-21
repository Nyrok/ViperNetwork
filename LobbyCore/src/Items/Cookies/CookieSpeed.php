<?php
namespace Nyrok\LobbyCore\Items\Cookies;

use Nyrok\LobbyCore\Items\CustomFood;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\entity\Living;

final class CookieSpeed extends CustomFood
{
    const NAME = "Cookie de Speed";
    const ID = 1001;

    public function onConsume(Living $consumer): void
    {
        $consumer->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), (8*60)*20, 0, true));
        parent::onConsume($consumer);
    }
}