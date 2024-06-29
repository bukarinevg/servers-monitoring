<?php

namespace app\enums;

use app\enums\ServerStatusEnum;

/**
 * This is an enum class that contains the server checks count.
 */
enum ServerChecksAmountEnum: int {
    case STATUS_FAILURE = 3;
    case STATUS_SUCCESS = 5;

    public static function getValue($status): int {
        return match ($status) {
            ServerStatusEnum::STATUS_FAILURE->value => self::STATUS_FAILURE->value,
            ServerStatusEnum::STATUS_SUCCESS->value => self::STATUS_SUCCESS->value 
        };
    }
}