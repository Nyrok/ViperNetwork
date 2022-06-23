<?php

namespace Nyrok\LobbyCore\Managers;

use Nyrok\LobbyCore\Core;
use Nyrok\LobbyCore\Forms\element\Button;
use Nyrok\LobbyCore\Forms\element\ModalOption;
use Nyrok\LobbyCore\Forms\utils\FormResponse;
use Nyrok\LobbyCore\Forms\variant\CustomForm;
use Nyrok\LobbyCore\Forms\element\Input;
use Nyrok\LobbyCore\Forms\element\Toggle;
use Nyrok\LobbyCore\Forms\variant\ModalForm;
use Nyrok\LobbyCore\Forms\variant\SimpleForm;
use Nyrok\LobbyCore\Player\ViperPlayer;
use pocketmine\player\Player;

abstract class FormsManager{

    public function __construct(){}

    public static function parametersForm(ViperPlayer $player): void
    {
        $params = $player->getPlayerProperties()->getProperties("parameters");
        $form = new CustomForm("Paramètres", function (Player $player, FormResponse $response) use ($params){
            $newarray = [];
            array_walk($params, function ($value, $name) use ($response, $newarray){
                $newarray[$name] = $response->getToggleSubmittedChoice($name);
            });
            $player->getPlayerProperties()->setProperties("parameters", $newarray);
        });
        array_walk($params, function ($value, $name) use ($form){
            $form->addElement($name, new Toggle(ucfirst($name), is_numeric($value) || $value === true));
        });
        $player->sendForm($form);
    }

    public static function knockBackForm(ViperPlayer $player): void {
        $form = new CustomForm("KnockBack", function (Player $player, FormResponse $response){
            Core::getInstance()->getConfig()->set("knockback", [
                "x" => $response->getInputSubmittedText("x"),
                "y" => $response->getInputSubmittedText("y"),
                "z" => $response->getInputSubmittedText("z"),
            ]);
        });
        $form->addElement("x", new Input('Valeur X', "0.40", ""));
        $form->addElement("y", new Input('Valeur Y', "0.40", ""));
        $form->addElement("z", new Input('Valeur Z', "0.40", ""));
        $player->sendForm($form);
    }

    public static function modesForm(Player $player): void
    {
        $form = new SimpleForm("Modes de Jeu", "Choisissez votre mode de jeu");
        foreach (Core::getInstance()->getConfig()->getNested("modes", []) as $name => $data) {
            $form->addButton(new Button($name, null, function (Player $player) use ($name){
                $mode = Core::getInstance()->getConfig()->getNested("modes.$name", []);
                self::confirmModeForm($player, $name, $mode);
            }));
        }
        $player->sendForm($form);
    }

    private static function confirmModeForm(Player $player, string $name, array $mode){
        $form = new ModalForm($name, $mode['motd'], new ModalOption("§aConfirmer"), new ModalOption("§cRetour"));
        $form->setAcceptListener(function (Player $player) use ($mode): void {
            $player->transfer($mode['ip'], $mode['port'], "Mode de Jeu");
        });
        $form->setDenyListener(function (Player $player) use ($mode): void {
            self::modesForm($player);
        });
        $player->sendForm($form);
    }
}