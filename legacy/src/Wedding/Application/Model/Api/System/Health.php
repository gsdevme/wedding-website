<?php

declare(strict_types=1);

namespace Wedding\Application\Model\Api\System;

class Health implements \JsonSerializable
{
    public function jsonSerialize()
    {
        return [
            'success' => 'ok',
        ];
    }
}
