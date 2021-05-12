<?php


namespace App\DataObjects\Cian;

use App\DataObjects\BaseObject;

class CoverageStatisticObject extends BaseObject
{
    /** @var int $offerId */
    protected $offerId;
    /** @var int $showsCount */
    protected $showsCount;
    /** @var int $searchesCount */
    protected $searchesCount;
    /** @var int $coverage */
    protected $coverage;

    protected function __construct(array $data)
    {
        parent::__construct($data);
        $this->offerId = $data['offerId'];
        $this->showsCount = $data['showsCount'];
        $this->searchesCount = $data['searchesCount'];
        $this->coverage = $data['coverage'];
    }

    /**
     * @return int
     */
    public function getOfferId(): int
    {
        return $this->offerId;
    }

    /**
     * @return int
     */
    public function getShowsCount(): int
    {
        return $this->showsCount;
    }

    /**
     * @return int
     */
    public function getSearchesCount(): int
    {
        return $this->searchesCount;
    }

    /**
     * @return int
     */
    public function getCoverage(): int
    {
        return $this->coverage;
    }
}
