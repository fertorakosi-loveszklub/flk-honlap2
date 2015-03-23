<?php

use App\News;

class NewsTest extends TestCase
{
    public function test_if_title_has_to_be_at_least_4_characters()
    {
        $news = new News;
        $news->title = 'xxx';

        $this->assertFalse($news->validate(), 'Failed asserting that the title has to be at least 4 characters.');
    }
}
