<?php

namespace Nyrok\LobbyCore\Objects;

use JetBrains\PhpStorm\Pure;
use Nyrok\LobbyCore\Player\ViperPlayer;
use pocketmine\math\Vector3;

final class FFA
{
    public function __construct(public Kit $kit, public array $area)
    {
    }

    /**
     * @return Kit
     */
    public function getKit(): Kit
    {
        return $this->kit;
    }

    /**
     * @return Vector3
     */
    public function getPosition(): Vector3
    {
        return new Vector3(
            mt_rand(min($this->getArea()[0]), max($this->getArea()[0])),
            max($this->getArea()[1]),
            mt_rand(min($this->getArea()[2]), max($this->getArea()[2]))
        );
    }

    /**
     * @return array
     */
    public function getArea(): array
    {
        return $this->area;
    }

    #[Pure] public function onArea(Vector3 $position): bool{
        return ($position->x >= min($this->getArea()[0]) and $position->x <= max($this->getArea()[0])) &&
            ($position->y >= min($this->getArea()[1]) and $position->y <= max($this->getArea()[1])) &&
            ($position->z >= min($this->getArea()[2]) and $position->z <= max($this->getArea()[2]));
    }

    public function load(ViperPlayer $player): void {
        $this->getKit()->send($player);
        $player->teleport($this->getPosition());
    }
}