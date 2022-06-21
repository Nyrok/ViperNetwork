<?php
namespace Nyrok\LobbyCore\Commands;

use Nyrok\LobbyCore\Core;
use Nyrok\LobbyCore\Managers\LanguageManager;
use Nyrok\LobbyCore\Objects\Language;
use Nyrok\LobbyCore\Player\ViperPlayer;
use Nyrok\LobbyCore\Traits\CommandTrait;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\lang\Translatable;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;

abstract class ViperCommands extends Command implements PluginOwned
{
    private const NAME = "";
    use CommandTrait;

    public function __construct(string $name = self::NAME, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
        self::setCommand($this);
        self::init();
    }

    public function getUsage(): Translatable|string
    {
        return LanguageManager::getPrefix().parent::getUsage();
    }

    public function getSenderLanguage(CommandSender $sender): ?Language {
        return (match($sender::class){
            ViperPlayer::class => $sender->getLanguage(),
            ConsoleCommandSender::class => LanguageManager::getDefaultLanguage(),
            default => null
        });
    }

    public function getOwningPlugin(): Plugin
    {
        return Core::getInstance();
    }
}