<?php

namespace App\Enum;

enum TicketStatusEnum: string
{
    case New = 'StatusNew';
    case InProgress = 'StatusInProgress';
    case InReview = 'StatusInReview';
    case Closed = 'StatusClosed';
}
