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

namespace Nyrok\LobbyCore\Traits;

trait UtilsTrait
{
    public function getRuntimeId(int $id): int {
        return $id + ($id > 0 ? 5000 : -5000);
    }

    public function checkName(string $name): string {
        $name = strtolower($name);
        $str = preg_replace('/\s+/', '-', $name);
        return "item." . $str . ".name";
    }
}