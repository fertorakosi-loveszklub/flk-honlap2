<?php

use App\News;

class NewsTest extends TestCase
{
    public function testIfTitleHasToBeAtLeast4Characters()
    {
        $news = new News();
        $news->title = 'xxx';

        $this->assertFalse($news->validate(), 'Failed asserting that the title has to be at least 4 characters.');
    }
}
