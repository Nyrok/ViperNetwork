<?php

namespace Nyrok\LobbyCore\Managers;

use Nyrok\LobbyCore\Forms\menu\Button;
use Nyrok\LobbyCore\Forms\MenuForm;
use Nyrok\LobbyCore\Player\ViperPlayer;

class UiManager{

    private ViperPlayer $player;

    public function __construct(ViperPlayer $player){
        $this->player = $player;
    }

    public function parametersUI(){
        $form = MenuForm::withOptions("Paramètres", "", array_keys($this->player->getPlayerProperties()->getProperties("parameters")), function (ViperPlayer $player, Button $selected){
           $form = MenuForm::withOptions($selected->text, "", ["Activer", "Désactiver"], function (ViperPlayer $player, Button $selected){
               if($selected->text === "Activer"){
                   $player->getPlayerProperties()->setNestedProperties($selected->text, true);
               }else{
                   $player->getPlayerProperties()->setNestedProperties($selected->text, false);
               }
           });
           $this->player->sendForm($form);
        });
        $this->player->sendForm($form);
    }
}