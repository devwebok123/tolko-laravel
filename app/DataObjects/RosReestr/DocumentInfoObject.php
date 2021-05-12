<?php


namespace App\DataObjects\RosReestr;

use App\DataObjects\BaseObject;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class DocumentInfoObject extends BaseObject
{
    public const STATUS_WAITING_PAID = 2;
    public const STATUS_JOB_IN_PROGRESS = 3;
    public const STATUS_COMPLETED = 4;
    public const STATUS_ERROR = 5;
    public const STATUS_CANCEL = 6;

    /** @var int $id */
    protected $id;
    /** @var string $type
     * Type from BlockOrder::TYPE....
     */
    protected $type;
    /** @var int $status */
    protected $status;
    /** @var Carbon|null $dateComplete */
    protected $dateComplete;
    /** @var int $price */
    protected $price;
    /** @var string $rrOrderNumber */
    protected $rrOrderNumber;
    /** @var int $egrnKeyId */
    protected $egrnKeyId;
    /** @var string $hash */
    protected $hash;
    /** @var string $note */
    protected $note;

    public function __construct(array $data)
    {
        parent::__construct($data);
        $document = Arr::first($data['documents']);
        $this->id = $document['id'];
        $this->type = $document['type'];
        $this->status = $document['status'];
        $this->dateComplete = $document['date_complete'] ? new Carbon($document['date_complete']) : null;
        $this->price = $document['price'];
        $this->rrOrderNumber = $document['rr_order_number'];
        $this->egrnKeyId = $document['egrn_key_id'];
        $this->hash = $document['hash'];
        $this->note = $document['note'];
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @return Carbon|null
     */
    public function getDateComplete(): ?Carbon
    {
        return $this->dateComplete;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @return string
     */
    public function getRrOrderNumber(): string
    {
        return $this->rrOrderNumber;
    }

    /**
     * @return int
     */
    public function getEgrnKeyId(): int
    {
        return $this->egrnKeyId;
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * @return string
     */
    public function getNote(): string
    {
        return $this->note;
    }
}
