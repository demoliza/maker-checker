<?php

namespace App\Enums;

enum RequestStatus:string
{
    case PENDING = '0';
    case COMPLETED = '1';
}