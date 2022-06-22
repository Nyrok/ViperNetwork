<?php

namespace Nyrok\LobbyCore\Managers;

use Nyrok\LobbyCore\Core;
use Nyrok\LobbyCore\Menu\InvMenuHandler;

abstract class MenuManager
{
    public static function initMenu(): void {
        if(!InvMenuHandler::isRegistered()){
            InvMenuHandler::register(Core::getInstance());
        }
        Core::getInstance()->getLogger()->notice("[MENU] InvMenu Virion Loaded");
    }
}