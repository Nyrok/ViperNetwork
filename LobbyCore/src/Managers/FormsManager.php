<?php

namespace Nyrok\LobbyCore\Managers;

use Nyrok\LobbyCore\Forms\menu\Button;
use Nyrok\LobbyCore\Forms\MenuForm;
use Nyrok\LobbyCore\Player\ViperPlayer;

abstract class FormsManager{

    private ViperPlayer $player;

    public function __construct(ViperPlayer $player){
        $this->player = $player;
    }

    public function parametersUI(){
        $form = MenuForm::withOptions("Paramètres", "", array_keys($this->player->getPlayerProperties()->getProperties("parameters")), function (ViperPlayer $player, Button $button){
           $form = MenuForm::withOptions($button->text, "", ["Activer", "Désactiver"], function (ViperPlayer $player, Button $selected) use ($button) {
               if($selected->text === "Activer"){
                   $player->getPlayerProperties()->setNestedProperties("parameters.".$button->text, true);
               }else{
                   $player->getPlayerProperties()->setNestedProperties("parameters.".$button->text, false);
               }
           });
           $this->player->sendForm($form);
        });
        $this->player->sendForm($form);
    }
}