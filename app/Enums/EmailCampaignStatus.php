<?php

namespace App\Enums;

class EmailCampaignStatus
{
    const DRAFT = 0;
    const QUEUEDFORSENDING = 1;
    const SENT = 3;
    const NOTENOUGHCREDITS = 4;
    const AWAITINGDELIVERY = 5;
    const SENDING = 6;
    const DELETED = 10;
}
