<?php

declare(strict_types=1);

namespace Wedding\Application\Model\Auth;

final class AuthenticationErrors
{
    public const USER_NOT_FOUND = 1331;
    public const INVALID_INVITE_CODE = 4412;
    public const GENERIC_ERROR = 4412;

    public const ERRORS = [
        1331 => 'invite_code_not_found',
        4412 => 'invalid_invite_code',
    ];
}
