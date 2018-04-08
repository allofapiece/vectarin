<?php

namespace App\Service;


use Ramsey\Uuid\Uuid;

class GenerateToken
{
    public function generate(string $stringForGenerateToken)
    {
        return Uuid::uuid5(Uuid::NAMESPACE_DNS, $stringForGenerateToken);
    }
}