<?php

declare(strict_types=1);

namespace Wedding\Application\Form\Model;

use Wedding\Application\Model\FoodChoices as FoodChoicesModel;
use Wedding\Domain\Entity\FoodChoice;
use Wedding\Domain\Entity\FoodType;
use Wedding\Domain\Entity\Guest;

class FoodChoices
{
    public const FIELD_REFERENCE = 'guest_%s_reference';
    public const FIELD_FOOD_TYPE = 'guest_%s_food_type_%s';
    public const FIELD_NAME = 'guest_%s_name';
    public const FIELD_DIET = 'guest_%s_diet';
    private const FIELD_SUBMIT = 'guest_submit';
    private const FIELD_FORM_REFERENCE = 'form_reference';

    private FoodChoicesModel $foodChoices;

    public function __construct(FoodChoicesModel $foodChoices)
    {
        $this->foodChoices = $foodChoices;
    }

    public function hasMultipleGuests(): bool
    {
        return $this->foodChoices->hasMultipleGuests();
    }

    public function getGuestReferences(): array
    {
        return array_filter(
            array_map(
                static function ($guest): ?string {
                    if (!$guest instanceof Guest) {
                        return null;
                    }

                    return $guest->getReference();
                },
                $this->foodChoices->getGuests()
            )
        );
    }

    public function getFieldName(string $guestReference, string $field): string
    {
        return sprintf($field, $guestReference);
    }

    public function getFieldNames(): array
    {
        $allFields = $this->fields();

        foreach ($this->foodChoices->getGuests() as $guest) {
            $allFields = array_merge($allFields, $this->getGuestFields($guest->getReference()));
        }

        return array_filter(
            array_map(
                static function (FormInputInterface $formInput) {
                    return $formInput->getName();
                },
                $allFields
            )
        );
    }

    public function isSubmitted(array $fields): bool
    {
        return isset($fields[self::FIELD_FORM_REFERENCE]) && $fields[self::FIELD_FORM_REFERENCE] === 'food-choices';
    }

    public function isValid(array $postData): bool
    {
        $fields = $this->getFieldNames();

        return count($fields) === count($postData) && empty(array_diff($fields, array_keys($postData)));
    }

    public function fields(): array
    {
        return [
            new FormInput(
                FormInput::TYPE_TEXT,
                self::FIELD_FORM_REFERENCE,
                'food-choices',
                [],
                ['hidden' => 'true'],
                ['readonly' => 'true']
            ),
            new FormInput(
                FormInput::TYPE_SUBMIT,
                self::FIELD_SUBMIT,
                'Submit',
                ['class' => 'center']
            ),
        ];
    }

    public function getGuestFields(string $guestReference): array
    {
        foreach ($this->foodChoices->getGuests() as $guest) {
            if (!$guest instanceof Guest || $guest->getReference() !== $guestReference) {
                continue;
            }

            $form = [
                new FormInput(
                    FormInput::TYPE_TEXT,
                    $this->getFieldName($guestReference, self::FIELD_REFERENCE),
                    $guest->getReference(),
                    [],
                    ['hidden' => 'true'],
                    ['readonly' => 'true'],
                ),
                new FormInput(
                    FormInput::TYPE_TEXT,
                    sprintf(self::FIELD_NAME, $guestReference),
                    $guest->getName(),
                    [],
                    ['readonly' => 'true'],
                ),
            ];

            $groups = [];

            foreach ($this->getLogicalOrderedFoodChoices() as $foodChoice) {
                if (!$foodChoice instanceof FoodChoice) {
                    continue;
                }

                $type = $foodChoice->getFoodType()->getName();

                if (!isset($groups[$type])) {
                    $groups[$type] = [];
                }

                $groups[$type][] = new FormInput(
                    FormInput::TYPE_RADIO,
                    $this->getFoodTypeFieldName($guestReference, $type, self::FIELD_FOOD_TYPE),
                    $foodChoice->getReference(),
                    [
                        'display' => $foodChoice->getName(),
                        'description' => $foodChoice->getDescription(),
                    ]
                );
            }

            foreach ($groups as $type => $group) {
                $form[] = new FormInputGroup(
                    $group,
                    [
                        'title' => $type,
                    ],
                    [
                        'id' => sprintf('%s-%s', $guestReference, $type),
                    ]
                );
            }

            $form[] = new FormInput(
                FormInput::TYPE_TEXTAREA,
                $this->getFieldName($guestReference, self::FIELD_DIET),
                '',
                [],
                ['maxlength' => '256'],
                ['placeholder' => '(Optional) Any further dietary requirements.'],
            );

            return $form;
        }

        return [];
    }

    public function getFoodTypeFieldName(string $guestReference, string $type, string $field): string
    {
        return sprintf(self::FIELD_FOOD_TYPE, $guestReference, $type);
    }

    private function getLogicalOrderedFoodChoices(): array
    {
        $foodChoices = $this->foodChoices->getFoodChoices();

        $sort = [
            FoodType::TYPE_DESSERT => 3,
            FoodType::TYPE_MAIN => 2,
            FoodType::TYPE_STARTER => 1,
        ];

        usort(
            $foodChoices,
            static function (FoodChoice $foodChoiceA, FoodChoice $foodChoiceB) use ($sort) {
                $a = $foodChoiceA->getFoodType()->getName();
                $b = $foodChoiceB->getFoodType()->getName();

                if ($sort[$a] > $sort[$b]) {
                    return 1;
                }

                if ($sort[$a] < $sort[$b]) {
                    return -1;
                }

                return 0;
            }
        );

        return $foodChoices;
    }
}
