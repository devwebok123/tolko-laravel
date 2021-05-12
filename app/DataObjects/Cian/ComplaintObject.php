<?php


namespace App\DataObjects\Cian;

use App\DataObjects\BaseObject;
use Carbon\Carbon;

class ComplaintObject extends BaseObject
{
    /** @var int $id */
    protected $id;
    /** @var int $userId */
    protected $userId;
    /** @var int $offerId */
    protected $offerId;
    /** @var string $text */
    protected $text;
    /** @var Carbon $creationDate */
    protected $creationDate;

    protected function __construct(array $data)
    {
        parent::__construct($data);

        $this->id = $data['id'];
        $this->userId = $data['userId'];
        $this->offerId = $data['offerId'];
        $this->text = $data['text'];
        $this->creationDate = new Carbon($data['creationDate']);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return int
     */
    public function getOfferId(): int
    {
        return $this->offerId;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return Carbon
     */
    public function getCreationDate(): Carbon
    {
        return $this->creationDate;
    }
}
