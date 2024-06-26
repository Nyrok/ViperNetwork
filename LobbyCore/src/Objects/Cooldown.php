<?php

namespace Nyrok\LobbyCore\Objects;

use JetBrains\PhpStorm\Pure;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\player\Player;

final class Cooldown
{
    /**
     * @var array
     */
    public array $cooldowns = [];

    /**
     * @param string $name
     * @param int $id
     * @param int $cooldown
     */
    public function __construct(private string $name, private int $id, private int $cooldown)
    {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getCooldown(): int
    {
        return $this->cooldown;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Item|null
     */
    public function getItem(): ?Item {
        return ItemFactory::getInstance()->get($this->id) ?? null;
    }

    /**
     * @param Player $player
     * @return bool
     */
    public function has(Player $player): bool {
        return ($this->cooldowns[$player->getName()] ?? 0) > time();
    }

    /**
     * @param Player $player
     */
    public function set(Player $player): void {
        $this->cooldowns[$player->getName()] = time() + $this->getCooldown();
    }

    /**
     * @param Player $player
     * @return int
     */
    #[Pure] public function get(Player $player): int {
        return $this->cooldowns[$player->getName()] ?? 0;
    }
}