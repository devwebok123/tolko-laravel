<?php


namespace App\DataObjects\Cian;

use App\DataObjects\BaseObject;

class ComplaintListObject extends BaseObject
{
    /** @var int $totalCount */
    protected $totalCount;
    /** @var ComplaintObject[] $complaints */
    protected $complaints;

    protected function __construct(array $data)
    {
        parent::__construct($data);

        $this->totalCount = $data['totalCount'];
        foreach ($data['items'] as $item) {
            $this->complaints[] = ComplaintObject::createFromArray($item);
        }
    }

    /**
     * @return int
     */
    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    /**
     * @return ComplaintObject[]
     */
    public function getComplaints(): array
    {
        return $this->complaints;
    }
}
