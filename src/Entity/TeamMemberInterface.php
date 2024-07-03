<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;

interface TeamMemberInterface
{
    public function getTeams(): Collection;

    public function addTeam(Team $team): static;
    

    public function removeTeam(Team $team): static;

}