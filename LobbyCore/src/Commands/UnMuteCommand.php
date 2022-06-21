<?php
namespace Nyrok\LobbyCore\Commands;

use Nyrok\LobbyCore\Player\ViperPlayer;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;

final class UnMuteCommand extends ViperCommands
{
    protected const NAME = "unmute";

    public function __construct(string $name = self::NAME, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if(isset($args[0])) {
            $player = $this->getOwningPlugin()->getServer()->getPlayer($args[0]);
            $sender_language = $this->getSenderLanguage($sender);
            switch ($player) {
                case $player instanceof ViperPlayer:
                    $player->getPlayerProperties()->setNestedProperties("status.muted", false);
                    $player->getLanguage()->getMessage("messages.mute.unmuted", ["{player}" => $sender->getName()])->send($player);
                    $sender_language?->getMessage("messages.mute.unmuter", ["{player}" => $player->getName()])->send($sender);
                    break;
                default:
                    $sender_language?->getMessage("messages.player.not-found")->send($sender);
                    break;
            }
        }
        else {
            $sender->sendMessage($this->getUsage());
        }
    }
}