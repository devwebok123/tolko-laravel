<?php


namespace App\DataObjects\RosReestr;

use App\DataObjects\BaseObject;
use Carbon\Carbon;

class TransactionInfoObject extends BaseObject
{

    /** @var int $id */
    protected $id;
    /** @var float $cost */
    protected $cost;
    /** @var bool $paid */
    protected $paid;
    /** @var string $paidFrom */
    protected $paidFrom;
    /** @var Carbon $dateCreate */
    protected $dateCreate;
    /** @var Carbon|null $datePaid */
    protected $datePaid;
    /** @var string|null $payConfirmLink */
    protected $payConfirmLink;
    /** @var string|null $payConfirmCode */
    protected $payConfirmCode;
    /** @var int $balance */
    protected $balance;

    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->id = $data['id'];
        $this->cost = $data['cost'];
        $this->paid = $data['paid'];
        $this->paidFrom = $data['paid_from'];
        $this->dateCreate = new Carbon($data['date_create']);
        if ($data['date_paid'] !== '0000-00-00 00:00:00') {
            $this->datePaid = new Carbon($data['date_paid']);
        }
        $this->balance = $data['pay_methods']['PA']['balance'];
        $this->payConfirmCode = $data['pay_methods']['PA']['confirm_code'];
        $this->payConfirmLink = $data['pay_methods']['PA']['confirm_link'];
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return float
     */
    public function getCost(): float
    {
        return $this->cost;
    }

    /**
     * @return bool
     */
    public function isPaid(): bool
    {
        return $this->paid;
    }

    /**
     * @return string
     */
    public function getPaidFrom(): string
    {
        return $this->paidFrom;
    }

    /**
     * @return Carbon
     */
    public function getDateCreate(): Carbon
    {
        return $this->dateCreate;
    }

    /**
     * @return Carbon|null
     */
    public function getDatePaid(): ?Carbon
    {
        return $this->datePaid;
    }

    /**
     * @return string
     */
    public function getPayConfirmLink(): string
    {
        return $this->payConfirmLink;
    }

    /**
     * @return string
     */
    public function getPayConfirmCode(): string
    {
        return $this->payConfirmCode;
    }

    /**
     * @return int
     */
    public function getBalance(): int
    {
        return $this->balance;
    }
}
