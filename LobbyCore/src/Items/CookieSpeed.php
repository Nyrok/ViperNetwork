<?php
namespace Nyrok\LobbyCore\Items;

use Nyrok\LobbyCore\Objects\CustomFood;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\entity\Living;
use pocketmine\item\VanillaItems;

final class CookieSpeed extends CustomFood
{
    const NAME = "CookieSpeed";

    public function onConsume(Living $consumer): void
    {
        $consumer->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), (8*60)*20, 0, true));
        parent::onConsume($consumer);
    }
}