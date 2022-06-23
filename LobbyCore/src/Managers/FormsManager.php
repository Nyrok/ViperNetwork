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
            $elements[] = new Toggle(ucfirst($name), is_numeric($value) || $value === true);
        }
        $form = new CustomForm("Paramètres",$elements, function (ViperPlayer $player, CustomFormResponse $response){
            $newarray = [];
            $parameters = array_keys($player->getPlayerProperties()->getProperties("parameters"));
            for ($count  = 0, $countMax = count($response->getValues()); $count < $countMax; $count++){
                $newarray[$parameters[$count]] = $response->getValues()[$count];
            }
            $player->getPlayerProperties()->setProperties("parameters", $newarray);
        });
        $player->sendForm($form);
    }
}