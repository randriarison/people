<?php

namespace App\Service\Interface;

use App\Entity\Occupation;

interface EmployedInterface
{
    public function getOccupation(): ?Occupation;
}