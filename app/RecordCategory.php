<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class RecordCategory extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'record_categories';

    public $timestamps = false;

    public function records()
    {
        return $this->hasMany('App\Record', 'category_id');
    }
}
