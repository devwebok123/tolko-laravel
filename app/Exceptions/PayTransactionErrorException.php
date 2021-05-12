<?php


namespace App\Exceptions;

use App\DataObjects\RosReestr\PayTransactionObject;

class PayTransactionErrorException extends \Exception
{

    /** @var PayTransactionObject $object */
    protected $object;

    public function __construct(PayTransactionObject $object)
    {
        $this->object = $object;
        parent::__construct('Pay transaction errors: ' . implode($object->getErrors()));
    }
}
