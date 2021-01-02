<?php

declare(strict_types=1);

namespace Wedding\Domain\Entity;

class FoodType
{
    public const TYPE_STARTER = 'starter';
    public const TYPE_MAIN = 'main';
    public const TYPE_DESSERT = 'dessert';

    private string $reference;

    private string $name;

    public function __construct(string $reference, string $name)
    {
        if (!in_array($name, ['starter', 'main', 'dessert'])) {
            throw new \InvalidArgumentException('Unsupported food type');
        }

        $this->reference = $reference;
        $this->name = $name;
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
