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
    }

    public function test_if_validation_is_successful_when_validator_reports_success()
    {
        $this->assertTrue($this->record->validate(), 'Failed to assert that the validation is successful when the validator reports success.');
    }

    public function test_if_imgurl_has_to_be_an_imgur_link()
    {
        $this->record->imgurl = 'http://test.com/something.jpg';

        $this->assertFalse($this->record->validate(), 'Failed asserting that the image URL has to be an Imgur link.');
    }

    public function test_if_category_has_to_exist_in_the_database()
    {
        // Category id is set to the highest category id. Increment it
        // so it doesn't exist in the database.
        $this->record->category++;

        $this->assertFalse($this->record->validate(), 'Failed asserting that the category of the record has to exist in the database.');
    }

    public function test_if_shots_amount_must_be_at_least_1()
    {
        $this->record->shots = 0;

        $this->assertFalse($this->record->validate(), 'Failed asserting that the shots amount must be at least 1.');
    }

    public function test_if_shots_amount_must_not_be_larger_than_30()
    {
        $this->record->shots = 31;

        $this->assertFalse($this->record->validate(), 'Failed asserting that the shots amount must not be larger than 30.');
    }

    public function test_if_points_must_be_at_least_1()
    {
        $this->record->points = 0;

        $this->assertFalse($this->record->validate(), 'Failed asserting that the points must be at least 1.');
    }

    public function test_if_points_must_not_be_larger_than_300()
    {
        $this->record->points = 301;

        $this->assertFalse($this->record->validate(), 'Failed asserting that the points must not be larger than 300.');
    }
}
