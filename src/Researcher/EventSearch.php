<?php

namespace App\Researcher;

use App\Entity\Campus;
use App\Entity\User;

class EventSearch
{
    public ?string $q ='';

    public ?Campus $campus = null;

    public ?User $user = null;
    public bool $isOrganizer = false;

    public bool $isRegistered = false;

    public bool $notRegistered = false;

    public bool $pastEvent = false;

    public ?\DateTimeInterface $startDate = null;

    public ?\DateTimeInterface $endDate = null;

}