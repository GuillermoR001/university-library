<?php


namespace App\Enums;

/**
 * Stablish the roles for the app
 */
enum UserRoles: string
{
    case STUDENT    = 'STUDENT';
    case LIBRARIAN  = 'LIBRARIAN';
}