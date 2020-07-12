<?php


namespace App\Calendar\App\Synchronization;


interface SynchronizationInterface
{
    /**
     * @param callable $job
     * @return mixed
     */
    public function transaction(callable $job);
}