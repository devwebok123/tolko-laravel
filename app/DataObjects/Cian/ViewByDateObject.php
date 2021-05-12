<?php


namespace App\DataObjects\Cian;

use App\DataObjects\BaseObject;
use Carbon\Carbon;

class ViewByDateObject extends BaseObject
{
    /** @var Carbon $date */
    protected $date;
    /** @var int $views */
    protected $views;

    protected function __construct(array $data)
    {
        parent::__construct($data);
        $this->date = Carbon::createFromFormat('Y-m-d', $data['date']);
        $this->views = $data['views'];
    }

    /**
     * @return Carbon
     */
    public function getDate(): Carbon
    {
        return $this->date;
    }

    /**
     * @return int
     */
    public function getViews(): int
    {
        return $this->views;
    }
}
