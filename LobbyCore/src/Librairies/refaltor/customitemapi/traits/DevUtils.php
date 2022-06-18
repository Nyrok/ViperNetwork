<?php

/*
 *    _______           _______ _________ _______  _______ __________________ _______  _______  _______  _______ _________
 *   (  ____ \|\     /|(  ____ \\__   __/(  ___  )(       )\__   __/\__   __/(  ____ \(       )(  ___  )(  ____ )\__   __/
 *   | (    \/| )   ( || (    \/   ) (   | (   ) || () () |   ) (      ) (   | (    \/| () () || (   ) || (    )|   ) (
 *   | |      | |   | || (_____    | |   | |   | || || || |   | |      | |   | (__    | || || || (___) || (____)|   | |
 *   | |      | |   | |(_____  )   | |   | |   | || |(_)| |   | |      | |   |  __)   | |(_)| ||  ___  ||  _____)   | |
 *   | |      | |   | |      ) |   | |   | |   | || |   | |   | |      | |   | (      | |   | || (   ) || (         | |
 *   | (____/\| (___) |/\____) |   | |   | (___) || )   ( |___) (___   | |   | (____/\| )   ( || )   ( || )      ___) (___
 *   (_______/(_______)\_______)   )_(   (_______)|/     \|\_______/   )_(   (_______/|/     \||/     \||/       \_______/
 *
 *   This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU Lesser General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   By: refaltor
 *   Discord: Refaltor#6969
 */

declare(strict_types=1);

namespace Nyrok\LobbyCore\Librairies\refaltor\customitemapi\traits;

use pocketmine\item\ArmorTypeInfo;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ToolTier;
use Nyrok\LobbyCore\Librairies\refaltor\customitemapi\items\BaseItem;
use Nyrok\LobbyCore\Librairies\refaltor\customitemapi\items\CustomArmor;
use Nyrok\LobbyCore\Librairies\refaltor\customitemapi\items\CustomAxe;
use Nyrok\LobbyCore\Librairies\refaltor\customitemapi\items\CustomFood;
use Nyrok\LobbyCore\Librairies\refaltor\customitemapi\items\CustomHoe;
use Nyrok\LobbyCore\Librairies\refaltor\customitemapi\items\CustomPickaxe;
use Nyrok\LobbyCore\Librairies\refaltor\customitemapi\items\CustomPotion;
use Nyrok\LobbyCore\Librairies\refaltor\customitemapi\items\CustomShovel;
use Nyrok\LobbyCore\Librairies\refaltor\customitemapi\items\CustomSword;

trait DevUtils
{
    public static function createItem(
        ItemIdentifier $identifier,
        string $name,
        string $texture_path,
        int $max_stack_size = 64,
        bool $allow_off_hand = false
    ): BaseItem {
        return new BaseItem($identifier, $name, $texture_path, $max_stack_size, $allow_off_hand);
    }

    public static function createFoodItem(
        ItemIdentifier $identifier,
        string $name,
        string $texture_path,
        int $foodRestore,
        float $saturationRestore,
        bool $isPotion = false,
        int $max_stack_size = 64,
        bool $can_always_eat = false,
    ): CustomFood|CustomPotion {
        if ($isPotion) {
            return new CustomPotion($identifier, $name, $texture_path, $can_always_eat, $foodRestore, $saturationRestore, $max_stack_size);
        } else return new CustomFood($identifier, $name, $texture_path, $can_always_eat, $foodRestore, $saturationRestore, $max_stack_size);
    }

    public static function createPickaxeItem(
        ItemIdentifier $identifier,
        string $name,
        ToolTier $tier,
        string $texture_path,
        float $miningSpeed,
        int $durability,
        int $attackPoints
    ): CustomPickaxe {
        return new CustomPickaxe(
            $identifier,
            $name,
            $tier,
            $texture_path,
            $miningSpeed,
            $durability,
            $attackPoints
        );
    }

    public static function createShovelItem(
        ItemIdentifier $identifier,
        string $name,
        ToolTier $tier,
        string $texture_path,
        float $miningSpeed,
        int $durability,
        int $attackPoints
    ): CustomShovel {
        return new CustomShovel(
            $identifier,
            $name,
            $tier,
            $texture_path,
            $miningSpeed,
            $durability,
            $attackPoints
        );
    }

    public static function createAxeItem(
        ItemIdentifier $identifier,
        string $name,
        ToolTier $tier,
        string $texture_path,
        float $miningSpeed,
        int $durability,
        int $attackPoints
    ): CustomAxe {
        return new CustomAxe(
            $identifier,
            $name,
            $tier,
            $texture_path,
            $miningSpeed,
            $durability,
            $attackPoints
        );
    }

    public static function createHoeItem(
        ItemIdentifier $identifier,
        string $name,
        ToolTier $tier,
        string $texture_path,
        float $miningSpeed,
        int $durability,
        int $attackPoints
    ): CustomHoe {
        return new CustomHoe(
            $identifier,
            $name,
            $tier,
            $texture_path,
            $durability,
            $attackPoints
        );
    }


    public static function createSwordItem(
        ItemIdentifier $identifier,
        string $name,
        ToolTier $tier,
        string $texture_path,
        float $miningSpeed,
        int $durability,
        int $attackPoints
    ): CustomSword {
        return new CustomSword(
            $identifier,
            $name,
            $tier,
            $texture_path,
            $durability,
            $attackPoints
        );
    }

    public static function createArmorItem(
        ItemIdentifier $identifier,
        string $name,
        ArmorTypeInfo $armorTypeInfo,
        string $textureName
    ): CustomArmor  {
        return new CustomArmor(
            $identifier,
            $name,
            $armorTypeInfo,
            $textureName
        );
    }
}