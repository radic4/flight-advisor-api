<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class AirportImportUploaded
{
    use Dispatchable;

    public $filename;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
    }
}
