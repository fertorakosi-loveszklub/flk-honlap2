<?php

use App\Album;

class AlbumTest extends TestCase
{
    public function test_if_title_has_to_be_at_least_3_characters()
    {
        $album = new Album();
        $album->title = 'x';
        $album->album_url = 'Loooooooooooooooooong test URL';

        $this->assertFalse($album->validate(), 'Failed asserting that title has to be at least 3 characters long.');
    }

    public function test_if_album_url_has_to_be_at_least_20_characters()
    {
        $album = new Album();
        $album->title = 'Test title';
        $album->album_url = 'x';

        $this->assertFalse($album->validate(), 'Failed asserting that album URL has to be at least 20 characters long.');
    }
}
