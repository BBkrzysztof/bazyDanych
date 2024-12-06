<?php

namespace App\Security\Enum;

enum UserRolesEnum: string
{
    case Admin = 'RoleAdmin';
    case Employee = 'RoleEmployee';
    case User = 'RoleUser';
}