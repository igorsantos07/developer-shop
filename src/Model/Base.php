<?php namespace Shop\Model;
use LaravelArdent\Ardent\Ardent;

class Base extends Ardent {

    protected $guarded = ['id']; //allowing mass-assignment of everything. Dangerous?

}