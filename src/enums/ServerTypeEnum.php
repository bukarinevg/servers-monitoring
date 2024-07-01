<?php

namespace app\enums;


enum ServerTypeEnum :int{
    case HTTP_SERVER =  0;
    case FTP_SERVER = 1;
    case SSH_SERVER = 2;
}