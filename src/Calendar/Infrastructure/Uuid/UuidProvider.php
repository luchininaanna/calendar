<?php


namespace App\Calendar\Infrastructure\Uuid;


use App\Calendar\App\Uuid\UuidProviderInterface;
use Ramsey\Uuid\Uuid;

class UuidProvider implements UuidProviderInterface
{
    public function generate(): string
    {
        return $uuid = Uuid::uuid4()->toString();
    }

    public function stringToBytes(string $string): string
    {
        return Uuid::fromString($string)->getBytes();
    }
}