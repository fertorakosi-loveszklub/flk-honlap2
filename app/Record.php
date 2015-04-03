<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Record extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'records';

    protected $validationRules = [
        'imgurl'        => 'regex:/^https?:\/\/i\.imgur\.com\/[a-zA-Z0-9]+\.jpe?g$/',
        'category'      => 'exists:record_categories,id',
        'shots'         => 'integer|between:1,30',
        'points'        => 'integer|between:1,300',
        'shot_at'       => 'date',
    ];

    public function category()
    {
        return $this->belongsTo('App\RecordCategory', 'category_id', 'id');
    }
}
