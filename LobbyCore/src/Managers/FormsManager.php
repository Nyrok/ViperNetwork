<?php

namespace Nyrok\LobbyCore\Managers;

use Nyrok\LobbyCore\Forms\menu\Button;
use Nyrok\LobbyCore\Forms\MenuForm;
use Nyrok\LobbyCore\Player\ViperPlayer;
use pocketmine\form\Form;
use pocketmine\player\Player;

abstract class FormsManager{

    public static function parametersUI(Player $player): Form {
        return MenuForm::withOptions("Paramètres", "", array_keys($player->getPlayerProperties()->getProperties("parameters")), function (ViperPlayer $player, Button $selected){
           $form = MenuForm::withOptions($selected->text, "", ["Activer", "Désactiver"], function (ViperPlayer $player, Button $selected){
               if($selected->text === "Activer"){
                   $player->getPlayerProperties()->setNestedProperties($selected->text, true);
               }else{
                   $player->getPlayerProperties()->setNestedProperties($selected->text, false);
               }
           });
            $player->sendForm($form);
        });
    }
}