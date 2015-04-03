<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Album extends BaseModel
{
    use SoftDeletes;

    /**
     * The validation rules.
     *
     * @var array
     */
    protected $validationRules = [
        'title'     => 'required|min:3',
        'album_url' => 'required|min:20',
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'albums';

    protected $dates = ['deleted_at'];
}
