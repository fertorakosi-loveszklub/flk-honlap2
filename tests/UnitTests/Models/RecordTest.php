<?php

use App\Record;

class RecordTest extends TestCase
{
    private $record;

    /**
     * Prepare for the test.
     * Migrate and seed database.
     * Create a test record with valid data.
     */
    public function setUp()
    {
        parent::setUp();
        Artisan::call('migrate');
        Artisan::call('db:seed');

        // Get highest record category id
        $max_record_id = DB::table('record_categories')->max('id');

        $this->record = new Record();
        $this->record->imgurl   = 'http://i.imgur.com/3hVkx2x.jpg';
        $this->record->category = $max_record_id;
        $this->record->shots    = 10;
        $this->record->points   = 100;
        $this->record->shot_at  = date('Y-m-d');

        $this->assertTrue($this->record->validate(), "Test object is not valid,
                          make sure everything is correct.");
    }

    public function testIfImageUrlHasToBeAValidImgurUrl()
    {
        $this->record->imgurl = 'http://test.com/something.jpg';

        $this->assertFalse($this->record->validate(), 'Failed asserting that the
            image URL has to be an Imgur link.');
    }

    public function testIfCategoryHasToExistInTheDatabase()
    {
        // Category id is set to the highest category id. Increment it
        // so it doesn't exist in the database.
        $this->record->category++;

        $this->assertFalse($this->record->validate(), 'Failed asserting that the
            category of the record has to exist in the database.');
    }

    public function testIfShotsAmoutNustBeAtLeast1()
    {
        $this->record->shots = 0;

        $this->assertFalse($this->record->validate(), 'Failed asserting that the
            shots amount must be at least 1.');
    }

    public function testIfShotsAmountMustNotBeLargerThan30()
    {
        $this->record->shots = 31;

        $this->assertFalse($this->record->validate(), 'Failed asserting that the
           shots amount must not be larger than 30.');
    }

    public function testIfPointsMustBeAtLeast1()
    {
        $this->record->points = 0;

        $this->assertFalse($this->record->validate(), 'Failed asserting that the
           points must be at least 1.');
    }

    public function testIfPointsMustNotBeLargerThan300()
    {
        $this->record->points = 301;

        $this->assertFalse($this->record->validate(), 'Failed asserting that the
            points must not be larger than 300.');
    }
}
