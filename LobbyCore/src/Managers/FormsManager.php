<?php

namespace Nyrok\LobbyCore\Managers;

use Nyrok\LobbyCore\Forms\menu\Button;
use Nyrok\LobbyCore\Forms\MenuForm;
use Nyrok\LobbyCore\Player\ViperPlayer;
use pocketmine\form\Form;
use pocketmine\player\Player;

abstract class FormsManager{

    public function __construct(public ViperPlayer $player){}

    public function parametersUI(): void
    {
        $form = MenuForm::withOptions("Paramètres", "", array_keys($this->player->getPlayerProperties()->getProperties("parameters")), function (ViperPlayer $player, Button $button){
            $form = MenuForm::withOptions($button->text, "", ["Activer", "Désactiver"], function (ViperPlayer $player, Button $selected) use ($button) {
                if($selected->text === "Activer"){
                    $player->getPlayerProperties()->setNestedProperties("parameters.".$button->text, true);
                }else{
                    $player->getPlayerProperties()->setNestedProperties("parameters.".$button->text, false);
                }
            });
            $player->sendForm($form);
        });
        $this->player->sendForm($form);
    }
}