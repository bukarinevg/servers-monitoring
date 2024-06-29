<?php

namespace app\enums;
/**
 * This is an enum class that contains the server status.
 */
enum ServerMessageEnum: string {
    case MESSAGE_SUCCESS = 'Healthy.';
    case MESSAGE_FAILURE = 'Unhealthy.';
    case MESSAGE_UNKNOWN = 'Status not defined.';
}