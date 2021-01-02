<?php

declare(strict_types=1);

namespace Wedding\Application\Form\Model;

/**
 * A DTO representing a HTML form
 */
interface FormInputInterface
{
    public const TYPE_TEXT = 'text';
    public const TYPE_TEXTAREA = 'textarea';
    public const TYPE_SUBMIT = 'submit';
    public const TYPE_RADIO = 'radio';

    /**
     * Represents a group of form inputs not actual form inputs
     *
     * This is uesful for radio buttons
     */
    public const TYPE_GROUP = 'group';

    public function getType(): string;

    public function getName(): string;

    public function getValue(): string;

    public function getAttributes(): string;
}
