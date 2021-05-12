<?php


namespace App\DataObjects\Cian;

use App\DataObjects\BaseObject;
use Carbon\Carbon;

class PhoneShowsByDayObject extends BaseObject
{
    /** @var Carbon $date */
    protected $date;
    /** @var $shows */
    protected $shows;

    protected function __construct(array $data)
    {
        parent::__construct($data);
        $this->date = Carbon::createFromFormat('Y-m-d', $data['date']);
        $this->shows = $data['phoneShows'];
    }

    /**
     * @return Carbon
     */
    public function getDate(): Carbon
    {
        return $this->date;
    }

    /**
     * @return mixed
     */
    public function getShows()
    {
        return $this->shows;
    }
}
