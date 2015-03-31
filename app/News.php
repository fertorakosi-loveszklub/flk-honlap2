<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends BaseModel
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'news';

    /**
     * The validation rules.
     *
     * @var array
     */
    protected $validationRules = [
        'title'     => 'required|min:4',
    ];

    protected $dates = ['deleted_at'];

    /**
     * Gets or sets the author of the news.
     *
     * @return App\User The autho of the news.
     */
    public function author()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
