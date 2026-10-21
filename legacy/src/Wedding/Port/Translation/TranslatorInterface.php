<?php

declare(strict_types=1);

namespace Wedding\Port\Translation;

interface TranslatorInterface
{
    /**
     * @param string $key
     * @param string $locale
     * @param array<string,string> $parameters
     * @return string
     */
    public function translate(string $key, string $locale, array $parameters = []): string;
}
