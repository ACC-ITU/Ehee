<?php

namespace App\Enums;

enum ActivityLogType: string
{
    case SEARCH = 'search';
    case LOGIN = 'login';
    case VIEW = 'view';
}
