<?php

namespace App\Services\Xml;

use DOMDocument;

class Doc extends DOMDocument
{
    use Attach;

    public function __construct($version = '1.0', $encoding = 'utf-8')
    {
        parent::__construct($version, $encoding);
    }

    /**
     * @return string
     */
    public static function getFolder(): string
    {
        return storage_path('app/public/');
    }
}
