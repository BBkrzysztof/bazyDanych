<?php

namespace Security\Enum;

enum UserRolesEnum: string
{
    case Admin = 'RoleAdmin';
    case Employee = 'RoleEmployee';
    case User = 'RoleUser';
}