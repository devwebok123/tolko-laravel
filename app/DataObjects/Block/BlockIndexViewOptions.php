<?php


namespace App\DataObjects\Block;

class BlockIndexViewOptions
{
    /** @var bool $hasSearchForm */
    protected $hasSearchForm = false;
    /** @var bool $hasMassMarketingForm */
    protected $hasMassMarketingForm = false;
    /** @var bool $onlyDrafts */
    protected $onlyDrafts = false;
    /** @var bool $addRowInfo */
    protected $addRowInfo;

    /**
     * BlockIndexViewOptions constructor.
     * @param bool $hasSearchForm
     * @param bool $hasMassMarketingForm
     * @param bool $onlyDrafts
     * @param bool $addRowInfo
     */
    protected function __construct(bool $hasSearchForm, bool $hasMassMarketingForm, bool $onlyDrafts, bool $addRowInfo)
    {
        $this->hasSearchForm = $hasSearchForm;
        $this->hasMassMarketingForm = $hasMassMarketingForm;
        $this->onlyDrafts = $onlyDrafts;
        $this->addRowInfo = $addRowInfo;
    }

    /**
     * @param bool $hasSearchForm
     * @param bool $hasMassMarketingForm
     * @param bool $onlyDrafts
     * @param bool $addRowInfo
     * @return BlockIndexViewOptions
     */
    public static function getInstance(
        bool $hasSearchForm,
        bool $hasMassMarketingForm,
        bool $onlyDrafts,
        bool $addRowInfo
    ): self {
        return new self($hasSearchForm, $hasMassMarketingForm, $onlyDrafts, $addRowInfo);
    }

    /**
     * @return bool
     */
    public function hasSearchForm(): bool
    {
        return $this->hasSearchForm;
    }

    /**
     * @return bool
     */
    public function hasMassMarketingForm(): bool
    {
        return $this->hasMassMarketingForm;
    }

    /**
     * @return bool
     */
    public function onlyDrafts(): bool
    {
        return $this->onlyDrafts;
    }

    /**
     * @return bool
     */
    public function getAddRowInfo(): bool
    {
        return $this->addRowInfo;
    }
}
