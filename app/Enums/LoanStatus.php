<?php

namespace App\Enums;

enum LoanStatus: string
{
    case OPEN = 'open';
    case CLOSED = 'closed';
}
