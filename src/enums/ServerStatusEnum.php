<?php
namespace app\enums;

/**
 * This is an enum class that contains the server status.
 */ 
enum ServerStatusEnum: int {
    case STATUS_FAILURE = 0;
    case STATUS_SUCCESS = 1;
}