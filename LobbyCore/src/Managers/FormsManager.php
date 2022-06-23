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
use pocketmine\Server;

abstract class FormsManager{

    public function __construct(){}

    public static function parametersForm(ViperPlayer $player): void
    {
        $params = $player->getPlayerProperties()->getProperties("parameters");
        $form = new CustomForm("Paramètres", function (Player $player, FormResponse $response) use ($params){
            $newarray = [];
            foreach ($player->getPlayerProperties()->getProperties("parameters") as $name => $value){
                $newarray[$name] = $response->getToggleSubmittedChoice($name);
            }
            $player->getPlayerProperties()->setProperties("parameters", $newarray);
        });
        foreach ($player->getPlayerProperties()->getProperties("parameters") as $name => $value){
            $form->addElement($name, new Toggle(ucfirst($name), is_numeric($value) || $value === true));
        }
        $player->sendForm($form);
    }

    public static function knockBackForm(ViperPlayer $player): void {
        $form = new CustomForm("KnockBack", function (Player $player, FormResponse $response){
            Core::getInstance()->getConfig()->set("knockback", [
                "x" => $response->getInputSubmittedText("x"),
                "y" => $response->getInputSubmittedText("y"),
                "z" => $response->getInputSubmittedText("z"),
            ]);
            Core::getInstance()->reloadConfig();
            KnockBackManager::initKnockBack();
        });
        $form->addElement("x", new Input('Valeur X', KnockBackManager::getKnockBackX(), "1"));
        $form->addElement("y", new Input('Valeur Y', KnockBackManager::getKnockBackY(), "3"));
        $form->addElement("z", new Input('Valeur Z', KnockBackManager::getKnockBackZ(), "1"));
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

    public static function gradesForm(ViperPlayer $player): void
    {
        $form = new SimpleForm("Grades", "");
        foreach (Server::getInstance()->getOnlinePlayers() as $target){
            $form->addButton(new Button($target->getName().", Grade: ".$target->getGrade()->getName(), null, function (Player $player) use ($target){
                self::setGradeForm($player, $target);
            }));
        }
        $player->sendForm($form);
    }

    public static function setGradeForm(Player $player, Player $target): void
    {
        $form = new SimpleForm("Grades", "");
        foreach (GradesManager::getGrades() as $name => $grade){
            $form->addButton(new Button($grade->getName(), null, function (Player $player) use ($name, $target){
                $target->getPlayerProperties()->setNestedProperties("infos.grade", $name);
                $player->getLanguage()->getMessage("messages.grades.set", ["{player}" => $target->getName(), "{grade}" => $name], true)->send($player);
            }));
        }
        $player->sendForm($form);
    }
}