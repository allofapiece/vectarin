<?php

declare(strict_types=1);

namespace App\Service\Util;


use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class TokenGenerator
{

    /**
     * @param string $stringForGenerateToken
     * @return UuidInterface
     */
    public function generate(string $stringForGenerateToken): UuidInterface
    {
        return Uuid::uuid5(Uuid::NAMESPACE_DNS, $stringForGenerateToken);
    }
}