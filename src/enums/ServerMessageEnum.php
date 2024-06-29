<?php

namespace app\enums;
/**
 * This is an enum class that contains the server status.
 */
enum ServerMessageEnum: string {
    case MESSAGE_SUCCESS = 'Successfully connected to the server.';
    case MESSAGE_FAILURE = 'Failed to connect to the server.';
    case MESSAGE_UNKNOWN = 'Status not defined.';
}