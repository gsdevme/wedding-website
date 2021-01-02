<?php

declare(strict_types=1);

namespace Wedding\Application\Form\Model;

final class FormInputGroup extends AbstractFormInput
{
    /**
     * @var array<FormInputInterface>
     */
    private array $inputs;

    /**
     * @var array<array>|array<null>
     */
    private array $attributes;

    /**
     * @var array<int,string>
     */
    private array $metadata;

    public function __construct(array $inputs, array $metadata, ?array ...$attributes)
    {
        if (count($inputs) < 1) {
            throw new \RuntimeException('FormInputGroup must contain at least one input');
        }

        $this->inputs = $inputs;
        $this->attributes = $attributes;
        $canonicalName = $inputs[0]->getName();
        $this->metadata = $metadata;

        parent::__construct(FormInputInterface::TYPE_GROUP, $canonicalName, '');
    }

    public function getInputs(): array
    {
        return $this->inputs;
    }

    public function getMetadata(): array
    {
        return $this->metadata;
    }

    public function getAttributes(): string
    {
        if (count($this->attributes) < 1) {
            return '';
        }

        return implode(
            ' ',
            array_map(
                static function (array $attribute) {
                    return sprintf('%s=%s', array_key_first($attribute), array_values($attribute)[0]);
                },
                $this->attributes
            )
        );
    }
}
