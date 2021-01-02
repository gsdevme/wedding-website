<?php

declare(strict_types=1);

namespace Wedding\Domain\Command;

use Wedding\Domain\Entity\FoodChoice;
use Wedding\Domain\Entity\FoodType;
use Wedding\Domain\Repository\Write\FoodChoiceRepositoryInterface;
use Wedding\Domain\Repository\Write\FoodTypeRepositoryInterface;
use Wedding\Port\Uuid\UuidGeneratorInterface;

class CreateFoodChoiceHandler
{
    private FoodChoiceRepositoryInterface $foodChoiceRepository;

    private UuidGeneratorInterface $uuidGenerator;

    private FoodTypeRepositoryInterface $foodTypeRepository;

    public function __construct(
        FoodChoiceRepositoryInterface $foodChoiceRepository,
        FoodTypeRepositoryInterface $foodTypeRepository,
        UuidGeneratorInterface $uuidGenerator
    ) {
        $this->foodChoiceRepository = $foodChoiceRepository;
        $this->uuidGenerator = $uuidGenerator;
        $this->foodTypeRepository = $foodTypeRepository;
    }

    public function __invoke(CreateFoodChoiceCommand $command): void
    {
        $foodType = $this->foodTypeRepository->findFoodTypeForName($command->getTypeName());

        if ($foodType === null) {
            $foodType = new FoodType($this->uuidGenerator->generate(), $command->getTypeName());

            $this->foodTypeRepository->create($foodType);
            $this->foodTypeRepository->save($foodType);
        }

        $foodChoice = new FoodChoice(
            $command->getReference(),
            $foodType,
            $command->getName(),
            $command->getDescription()
        );

        $this->foodChoiceRepository->create($foodChoice);
    }
}
