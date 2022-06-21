<?php
namespace Nyrok\LobbyCore\Commands;

use Nyrok\LobbyCore\Player\ViperPlayer;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;

final class UnFreezeCommand extends ViperCommands
{
    protected const NAME = "unfreeze";

    public function __construct(string $name = self::NAME, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if(isset($args[0])){
            $player = $this->getOwningPlugin()->getServer()->getPlayerByPrefix($args[0]);
            $sender_language = $this->getSenderLanguage($sender);
            switch ($player) {
                case $player instanceof ViperPlayer:
                    $player->setImmobile(false);
                    $player->getPlayerProperties()->setNestedProperties("status.freezed", false);
                    $player->getLanguage()->getMessage("messages.freeze.unfreezed", ["{player}" => $sender->getName()])->send($player);
                    $sender_language?->getMessage("messages.freeze.unfreezer", ["{player}" => $player->getName()])->send($sender);
                    break;
                default:
                    $sender_language?->getMessage("messages.player.not-found")->send($sender);
                    break;
            }
        }
        else{
            $sender->sendMessage($this->getUsage());
        }
    }
}