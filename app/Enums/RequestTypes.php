<?php

namespace App\Enums;

enum RequestTypes:string
{
    case CREATE = 'create';
    case UPDATE = 'update';
    case DELETE = 'delete';
}