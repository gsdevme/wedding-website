<?php

declare(strict_types=1);

namespace Wedding\Application\Form\Model;

/**
 * A DTO representing a HTML form
 */
abstract class AbstractFormInput implements FormInputInterface
{
    private string $type;

    private string $name;

    private string $value;

    /**
     * @var array<string,string>
     */
    private array $attributes;

    /**
     * @var array<int,string>
     */
    private array $metadata;

    public function __construct(string $type, string $name, string $value, array $metadata = [], ?array ...$attributes)
    {
        $this->type = $type;
        $this->name = $name;
        $this->value = $value;
        $this->metadata = $metadata;
        $this->attributes = $attributes ?: []; /** @phpstan-ignore-line */
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getMetadata(): array
    {
        return $this->metadata;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): string
    {
        return $this->value;
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
                    return sprintf('%s="%s"', array_key_first($attribute), array_values($attribute)[0]);
                },
                $this->attributes
            )
        );
    }
}
