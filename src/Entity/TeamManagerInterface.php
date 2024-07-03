<?php

namespace App\Entity;

interface TeamManagerInterface
{
    public function getCompany(): Company;

    public function getUser(): User;

    public function getRoles(): array;
}