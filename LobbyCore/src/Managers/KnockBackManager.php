<?php

namespace Nyrok\LobbyCore\Managers;

use Nyrok\LobbyCore\Core;

abstract class KnockBackManager
{
    public static function initKnockBack(): void {
        $kb = Core::getInstance()->getConfig()->get("knockback", []);
        if(!empty($kb)){
            Core::getInstance()->getLogger()->notice("[KNOCKBACK] X: {$kb["x"]}, Y: {$kb["y"]}, Z: {$kb["z"]} Loaded");
        }
        else {
            Core::getInstance()->getLogger()->error("[KNOCKBACK] Cannot Load KnockBack Settings");
        }
    }

    private static function getKnockBackX(): float {
        return Core::getInstance()->getConfig()->getNested("knockback.x", 0.40);
    }

    public static function getKnockBackY(): float {
        return Core::getInstance()->getConfig()->getNested("knockback.y", 0.40);
    }

    private static function getKnockBackZ(): float {
        return Core::getInstance()->getConfig()->getNested("knockback.z", 0.40);
    }

    public static function getKnockBackForce(): float {
        return self::getKnockBackX() ** self::getKnockBackZ();
    }


}