<?php


namespace App\DataObjects\Cian;

use App\DataObjects\BaseObject;

class ViewsByDatesObject extends BaseObject
{
    /** @var int $offerId */
    protected $offerId;
    /** @var PhoneShowsByDayObject[] $phoneShowByDays */
    protected $phoneShowByDays = [];
    /** @var ViewsByDatesObject[] $viewsByDays */
    protected $viewsByDays = [];

    protected function __construct(array $data)
    {
        parent::__construct($data);
        $this->offerId = $data['offerId'];

        foreach ($data['phoneShowsByDays'] as $phoneShowsByDay) {
            $this->phoneShowByDays[] = PhoneShowsByDayObject::createFromArray($phoneShowsByDay);
        }
        foreach ($data['viewsByDays'] as $viewsByDay) {
            $this->viewsByDays[] = ViewByDateObject::createFromArray($viewsByDay);
        }
    }

    /**
     * @return int
     */
    public function getOfferId(): int
    {
        return $this->offerId;
    }

    /**
     * @return PhoneShowsByDayObject[]
     */
    public function getPhoneShowByDays(): array
    {
        return $this->phoneShowByDays;
    }

    /**
     * @return ViewsByDatesObject[]
     */
    public function getViewsByDays(): array
    {
        return $this->viewsByDays;
    }
}
