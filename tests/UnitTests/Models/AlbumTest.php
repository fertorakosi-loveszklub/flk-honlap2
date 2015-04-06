<?php

use App\Album;

class AlbumTest extends TestCase
{
    public function testIfTitleHasToBeAtLeast3Characters()
    {
        $album = new Album();
        $album->title = 'x';
        $album->album_url = 'Loooooooooooooooooong test URL';

        $this->assertFalse($album->validate(), 'Failed asserting that title has to be at least 3 characters long.');
    }

    public function testIfAlbumUrlHasToBeAtLeast3Characters()
    {
        $album = new Album();
        $album->title = 'Test title';
        $album->album_url = 'x';

        $this->assertFalse($album->validate(), 'Failed asserting that album URL has to be at least 20 characters long.');
    }
}
