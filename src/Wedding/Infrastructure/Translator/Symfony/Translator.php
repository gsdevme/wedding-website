<?php

declare(strict_types=1);

namespace Wedding\Infrastructure\Translator\Symfony;

use Symfony\Contracts\Translation\TranslatorInterface as SymfonyTranslator;
use Wedding\Port\Translation\TranslatorInterface;

class Translator implements TranslatorInterface
{
    private SymfonyTranslator $translator;

    public function __construct(SymfonyTranslator $translator)
    {
        $this->translator = $translator;
    }

    public function translate(string $key, string $locale, array $parameters = []): string
    {
        return strval($this->translator->trans($key, $parameters, null, $locale));
    }
}
