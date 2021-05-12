<?php


namespace App\DataObjects;

class BaseObject
{

    protected function __construct(array $data)
    {
    }

    public static function createFromArray(array $data): self
    {
        return new static($data);
    }
}
