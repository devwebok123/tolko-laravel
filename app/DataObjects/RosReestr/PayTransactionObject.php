<?php


namespace App\DataObjects\RosReestr;

use App\DataObjects\BaseObject;

class PayTransactionObject extends BaseObject
{
    /** @var bool $paid */
    protected $paid;
    /** @var float $cost */
    protected $cost;
    /** @var string[] */
    protected $errors;

    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->paid = $data['paid'];
        $this->cost = $data['cost'];
        $this->errors = $data['error'];
    }

    /**
     * @return bool
     */
    public function isPaid(): bool
    {
        return $this->paid;
    }

    /**
     * @return float
     */
    public function getCost(): float
    {
        return $this->cost;
    }

    /**
     * @return string[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
