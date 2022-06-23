<?php
namespace Nyrok\LobbyCore\Commands;

use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;

final class UnBanIPCommand extends ViperCommands
{
    protected const NAME = "unban-ip";

    public function __construct(string $name = self::NAME, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if(parent::execute($sender,$commandLabel, $args))
        if(isset($args[0])) {
            $player = $this->getOwningPlugin()->getServer()->getOfflinePlayer($args[0])?->getAddress() ?? substr_count($args[0], ".") === 4 ? $args[0] : null;
            $sender_language = $this->getSenderLanguage($sender);
            if($player){
                $this->getOwningPlugin()->getServer()->getIPBans()->remove($player->getName());
                $sender_language?->getMessage("messages.ban-ip.unbanned", ["{player}" => $player])?->send($sender);

            } else{
                $sender_language?->getMessage("messages.player.not-found")->send($sender);
            }
        }
        else {
            $sender->sendMessage($this->getUsage());
        }
    }
}