<?php

declare(strict_types=1);

namespace Wedding\Application\Form\Handler;

use Wedding\Application\Form\Model\FoodChoices;
use Wedding\Domain\Command\SetFoodChoicesCommand;
use Wedding\Domain\Entity\FoodType;
use Wedding\Port\MessageBus\CommandBusInterface;

class FoodChoicesFormHandler
{
    private CommandBusInterface $commandBus;

    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function handle(FoodChoices $foodChoices, array $fields): void
    {
        foreach ($foodChoices->getGuestReferences() as $reference) {
            $guestField = $foodChoices->getFieldName($reference, FoodChoices::FIELD_REFERENCE);
            $starterField = $foodChoices->getFoodTypeFieldName(
                $reference,
                FoodType::TYPE_STARTER,
                FoodChoices::FIELD_FOOD_TYPE
            );
            $mainField = $foodChoices->getFoodTypeFieldName(
                $reference,
                FoodType::TYPE_MAIN,
                FoodChoices::FIELD_FOOD_TYPE
            );
            $dessertField = $foodChoices->getFoodTypeFieldName(
                $reference,
                FoodType::TYPE_DESSERT,
                FoodChoices::FIELD_FOOD_TYPE
            );

            $specialRequirements = strval($foodChoices->getFieldName($reference, FoodChoices::FIELD_DIET));

            if (!isset($fields[$guestField], $fields[$starterField], $fields[$mainField], $fields[$dessertField])) {
                throw new \InvalidArgumentException(); // @todo handle this
            }

            $starter = strval($fields[$starterField]);
            $main = strval($fields[$mainField]);
            $dessert = strval($fields[$dessertField]);
            $specialRequirements = $fields[$specialRequirements] ?? '';

            $this->commandBus->handle(
                new SetFoodChoicesCommand(
                    $reference,
                    $starter,
                    $main,
                    $dessert,
                    strval($specialRequirements)
                )
            );
        }
    }
}
