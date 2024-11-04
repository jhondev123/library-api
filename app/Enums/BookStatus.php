<?php

namespace App\Enums;

/**
 *
 * Enum BookStatus
 *
 *  Esse enumerado representa os estados possíveis do status de um livro.
 */
enum BookStatus: string
{
    case Available = 'available';
    case Unavailable = 'unavailable';

}
