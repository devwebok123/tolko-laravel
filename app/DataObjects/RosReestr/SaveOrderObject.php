<?php


namespace App\DataObjects\RosReestr;

use App\DataObjects\BaseObject;

class SaveOrderObject extends BaseObject
{
    /** @var int $transactionId */
    protected $transactionId;
    /** @var string $documentType */
    protected $documentType;
    /** @var int $documentId */
    protected $documentId;
    /** @var bool $paid */
    protected $paid;

    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->transactionId = $data['transaction_id'];
        $this->paid = $data['paid'];
        foreach ($data['documents_id'] as $documentType => $documentId) {
            $this->documentId = $documentId;
            $this->documentType = $documentType;
        }
    }

    /**
     * @return int
     */
    public function getTransactionId(): int
    {
        return $this->transactionId;
    }

    /**
     * @return string
     */
    public function getDocumentType(): string
    {
        return $this->documentType;
    }

    /**
     * @return int
     */
    public function getDocumentId(): int
    {
        return $this->documentId;
    }

    /**
     * @return bool
     */
    public function isPaid(): bool
    {
        return $this->paid;
    }
}
