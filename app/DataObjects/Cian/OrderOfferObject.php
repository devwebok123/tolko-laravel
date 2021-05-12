<?php


namespace App\DataObjects\Cian;

use App\DataObjects\BaseObject;

class OrderOfferObject extends BaseObject
{
    protected const STATUS_BLOCKED = 'Blocked';
    protected const STATUS_DEACTIVATED = 'Deactivated';
    protected const STATUS_DELETED = 'Deleted';
    protected const STATUS_DRAFT = 'Draft';
    protected const STATUS_MODERATE = 'Moderate';
    protected const STATUS_PUBLISHED = 'Published';
    protected const STATUS_REFUSED = 'Refused';
    protected const STATUS_REMOVED_BY_MODERATOR = 'RemovedByModerator';
    protected const STATUS_SOLD = 'Sold';


    /** @var int $offerId */
    protected $offerId;
    /** @var int $blockId */
    protected $blockId;

    /** @var string $status */
    protected $status;
    /** @var string[] $errors */
    protected $errors;
    /** @var string[] $warnings */
    protected $warnings;
    /** @var string|null $url */
    protected $url;

    protected function __construct(array $data)
    {
        parent::__construct($data);
        $this->offerId = (int)$data['offerId'];
        $this->blockId = $data['externalId'];
        $this->status = $data['status'];
        $this->errors = $data['errors'];
        $this->warnings = $data['warnings'];
        $this->url = $data['url'];
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
    public function getBlockId(): int
    {
        return $this->blockId;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return string[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return string[]
     */
    public function getWarnings(): array
    {
        return $this->warnings;
    }

    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }
}
