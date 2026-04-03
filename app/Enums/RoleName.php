<?php

namespace App\Enums;

enum RoleName: string
{
    case Customer = 'customer';
    case Owner = 'owner';
    case Admin = 'admin';
}
