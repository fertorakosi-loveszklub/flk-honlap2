<?php
namespace App\Libraries;

class UrlBeautifier
{
    /**
     * Beautifies a URL. Removes whitespace characters and transliterates
     * umlauts and HTML entities to readable latin latters.
     *
     * @param string $url URL to beautify.
     *
     * @return string Beautiful URL.
     */
    public static function beautify($url)
    {
        // Replace HTML entities with latin letters without umlauts.
        $url = html_entity_decode($url);

        // Remove spaces
        $url = preg_replace('/[ ]+/', '-', $url);

        // Umlauts and such
        setlocale(LC_CTYPE, 'en_US.UTF-8');
        $url = iconv("utf-8", "ASCII//TRANSLIT//IGNORE", $url);

        // Ignore everything else
        $url = preg_replace("/[^a-zA-Z0-9\-]/", '', $url);

        // Finally, replace multiple hyphens with a single one
        $url = preg_replace('/\-+/', '-', $url);

        return $url;
    }
}
