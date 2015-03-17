<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model {
    
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'news';

    protected $dates = ['deleted_at'];

    /**
     * Gets or sets the author of the news.
     * @return App\User The autho of the news.
     */
    public function author()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    /**
     * Generates a friendly URL.
     * @param  string $string String to friendlify.
     * @return string         Friendly URL.
     */
    public static function urlFriendlify($string) {
        // Normalize
        $string = News::urlNormalize($string);

        // Spaces
        $string = preg_replace('/\\s+/', '-', $string);

        // Umlauts and such
        setlocale(LC_CTYPE, 'en_US.UTF-8');
        $string = iconv("utf-8","ASCII//TRANSLIT//IGNORE", $string);

        // Ignore everything else
        $string = preg_replace("/[^a-zA-Z0-9\-]/", '', $string);

        return $string;
    }

    /**
     * Normalizes a URL.
     * @param  string $string String to normalize.
     * @return string         Normalized URL.
     */
    public static function urlNormalize($string) {
        // Transliterate
        $string = str_replace(array('&#368;', '&#336;', '&#369;', '&#337;'), array('Ű', 'Ő', 'ű', 'ő'), $string);
        $string = str_replace(array('&Aacute;', '&aacute;', '&Eacute;', '&eacute;', '&Iacute;', '&iacute;', '&Oacute;',
            '&oacute;', '&Ouml;', '&ouml;', '&Uacute;', '&uacute;', '&Uuml;', '&uuml;'),
            array('Á', 'á', 'É', 'e', 'Í', 'í', 'Ó', 'ó', 'Ö', 'ö', 'Ú', 'ú', 'Ü', 'Ű'), $string);

        return $string;
    }

}