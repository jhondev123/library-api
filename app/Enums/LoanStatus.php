<?php

namespace App\Enums;

/**
 * Enumeração dos status de um empréstimo.
 */
enum LoanStatus: string
{
    case OPEN = 'open';
    case CLOSED = 'closed';
}
