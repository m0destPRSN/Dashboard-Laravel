<?php

namespace App\Interfaces;

interface LocationRepository
{
    public function search(string $query = '');
}
