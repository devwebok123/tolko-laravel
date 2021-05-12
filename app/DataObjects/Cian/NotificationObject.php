<?php


namespace App\DataObjects\Cian;

use App\DataObjects\BaseObject;
use Carbon\Carbon;

class NotificationObject extends BaseObject
{
    /** @var int $id */
    protected $id;
    /** @var string $text */
    protected $text;
    /** @var Carbon $date */
    protected $date;

    protected function __construct(array $data)
    {
        parent::__construct($data);

        $this->id = $data['id'];
        $this->text = $data['text'];
        $this->date = new Carbon($data['date']);
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
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return Carbon
     */
    public function getDate(): Carbon
    {
        return $this->date;
    }
}
