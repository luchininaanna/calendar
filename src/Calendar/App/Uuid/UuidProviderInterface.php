<?php


namespace App\Calendar\App\Uuid;


interface UuidProviderInterface
{
    public function generate(): string;

    public function stringToBytes(string $string): string;
}