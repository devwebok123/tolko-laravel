<?php

namespace App\Services\Xml;

use DOMElement;

class Node extends DOMElement
{
    use Attach;

    public function rAttach(array $keyVals): void
    {
        array_walk($keyVals, function ($val, $key) {
            is_array($val)
                ? ($this->attach($key))->rAttach($val)
                : $this->attach($key, $val);
        });
    }
}
