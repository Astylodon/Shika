<?php

namespace Shika\Helpers;

use MaxMind\Db\Reader;

class GeoLocation
{
    private ?Reader $reader = null;

    public function __construct(string $database)
    {
        if (empty($database) || !is_file($database))
        {
            return;
        }

        $this->reader = new Reader($database);
    }

    /**
     * Gets whether the Maxmind database is available
     */
    public function isAvailable(): bool
    {
        return $this->reader !== null;
    }

    /**
     * Gets the country of an IP address
     */
    public function getCountry(string $address): ?array
    {
        $record = $this->reader->get($address);
        
        if ($record === null)
        {
            return null;
        }
        
        return $record["country"];
    }
}