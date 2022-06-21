<?php

namespace Nyrok\LobbyCore\Managers;

use Nyrok\LobbyCore\Forms\CustomForm;
use Nyrok\LobbyCore\Forms\CustomFormResponse;
use Nyrok\LobbyCore\Forms\element\Toggle;
use Nyrok\LobbyCore\Player\ViperPlayer;

abstract class FormsManager{

    public function __construct(){}

    public static function parametersUI(ViperPlayer $player): void
    {
        $elements =  [];
        foreach ($player->getPlayerProperties()->getProperties("parameters") as $name => $value){
            $elements[] = new Toggle(ucfirst($name), is_numeric($value));
        }
        $form = new CustomForm("Paramètres",$elements, function (ViperPlayer $player, CustomFormResponse $response){
            $toogle = $response->getToggle();
            var_dump($toogle->text, $toogle->getValue());
            $player->getPlayerProperties()->setNestedProperties("parameters.".strtolower($toogle->text), $toogle->getValue());
        });
        $player->sendForm($form);
    }
}