<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;

/**
 * class DadataException
 *
 */
class DadataException extends Exception
{
    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  Request
     */
    public function render($request)
    {
        dd($this->message);
    }
}
