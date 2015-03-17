<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Record extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'records';

    public function category()
    {
        return $this->belongsTo('App\RecordCategory', 'category_id', 'id');
    }
}