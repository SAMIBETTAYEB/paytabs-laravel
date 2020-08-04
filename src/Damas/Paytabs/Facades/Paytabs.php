<?php namespace Damas\Paytabs\Facades;

use Damas\Paytabs\Paytabs as PaytabsClassName;
use Illuminate\Support\Facades\Facade;

class Paytabs extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() {
        return PaytabsClassName::class;
    }

}