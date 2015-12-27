<?php namespace Shop\Model;
use LaravelArdent\Ardent\Ardent;

class Base extends Ardent {

    protected $guarded = ['id']; //allowing mass-assignment of everything. Dangerous?

    //TODO: this should be a job for the API... but how would the API know the decimal fields for the entire system?
    protected static function decimal($float) {
        return number_format(round($float, 2), 2, '.', '');
    }

}