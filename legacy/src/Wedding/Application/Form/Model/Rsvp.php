<?php

declare(strict_types=1);

namespace Wedding\Application\Form\Model;

use Wedding\Application\Model\Rsvp as RsvpModel;
use Wedding\Domain\Entity\Guest;

class Rsvp
{
    public const FIELD_REFERENCE = 'guest_%s_reference';
    private const FIELD_NAME = 'guest_%s_name';
    public const FIELD_RSVP = 'guest_%s_rsvp';
    private const FIELD_SUBMIT = 'guest_submit';
    private const FIELD_FORM_REFERENCE = 'form_reference';

    public const ATTENDING_TRUE = 'I\'ll be there';
    public const ATTENDING_FALSE = 'Sorry, I can\'t make it';

    private RsvpModel $rsvp;

    public function __construct(RsvpModel $rsvp)
    {
        $this->rsvp = $rsvp;
    }

    public function hasMultipleGuests(): bool
    {
        return $this->rsvp->hasMultipleGuests();
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
                $this->rsvp->getGuests()
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

        foreach ($this->rsvp->getGuests() as $guest) {
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

    public function isSubmitted(array $postData): bool
    {
        return isset($postData[self::FIELD_FORM_REFERENCE]) && $postData[self::FIELD_FORM_REFERENCE] === 'rsvp';
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
                'rsvp',
                [],
                ['hidden' => 'true'],
                ['readonly' => 'true']
            ),
            new FormInput(
                FormInput::TYPE_SUBMIT,
                self::FIELD_SUBMIT,
                'RSVP',
                [],
                ['class' => 'center']
            ),
        ];
    }

    public function getGuestFields(string $guestReference): array
    {
        foreach ($this->rsvp->getGuests() as $guest) {
            if (!$guest instanceof Guest || $guest->getReference() !== $guestReference) {
                continue;
            }

            return [
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
                new FormInputGroup(
                    [
                        new FormInput(
                            FormInput::TYPE_RADIO,
                            sprintf(self::FIELD_RSVP, $guestReference),
                            self::ATTENDING_TRUE
                        ),
                        new FormInput(
                            FormInput::TYPE_RADIO,
                            sprintf(self::FIELD_RSVP, $guestReference),
                            self::ATTENDING_FALSE
                        ),
                    ],
                    [],
                    [
                        'id' => $guestReference,
                    ]
                ),
            ];
        }

        return [];
    }
}
