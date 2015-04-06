<?php

use App\Member;


class MemberTest extends TestCase
{   
    private $member;

    /** Prepare tests, create a valid test object */
    public function setUp()
    {
        parent::setUp();

        Artisan::call('migrate');
        Artisan::call('db:seed');

        $this->member = new Member;
        $this->member->name         = "Test Name";
        $this->member->birth_date   = "2000-01-01";
        $this->member->birth_place  = "Test Address 1";
        $this->member->mother_name  = "Test Mother Name";
        $this->member->address      = "Test Address 2";
        $this->member->member_since = "2010-01-01";

        $this->assertTrue($this->member->validate(null, 
                            ["name", "birth_place", "address", "mother_name"]),
                            'Test object is invalid, make sure everything is correct.');
    }

    public function testIfIspaidIsFalseWhenPaymentsAreNull()
    {
        // Partially mock the Member object
        $member = Mockery::mock('App\Member[getPaidUntil]');
        $member->shouldReceive('getPaidUntil')
               ->once()
               ->andReturn(null);

        $this->assertFalse($member->isPaid(), 'Failed to assert that 
                             $member->isPaid() returns false when there are no 
                             associated payments.');
    }

    public function testIfIspaidIsFalseWhenLatestPaymentIsEarlierThanToday()
    {
        // Set the date to 1990-01-01
        $date = "1990-01-01";

        // Partially mock the Member object
        $member = Mockery::mock('App\Member[getPaidUntil]');
        $member->shouldReceive('getPaidUntil')
               ->once()
               ->andReturn($date);

        $this->assertFalse($member->isPaid(), 'Failed to assert that 
                             $member->isPaid() returns false when the latest 
                             associated payment is earlier than today.');
    }

    public function testIfIspaidIsTrueWhenLatestPaymentIsToday()
    {
        // Set the date to today
        $date = (new \DateTime('now'))->format('Y-m-d');

        // Partially mock the Member object
        $member = Mockery::mock('App\Member[getPaidUntil]');
        $member->shouldReceive('getPaidUntil')
               ->once()
               ->andReturn($date);

        $this->assertTrue($member->isPaid(), 'Failed to assert that 
                             $member->isPaid() returns true when the latest 
                             associated payment is today.');
    }

    public function testIfIspaidIsTrueWhenLatestPaymentIsLaterThanToday()
    {
        // Set the date to 2999-12-31
        // If it is already 3000, well, hello there, please set this string to
        // 2999-12-31 ;)
        $date = "2999-12-31";

        // Partially mock the Member object
        $member = Mockery::mock('App\Member[getPaidUntil]');
        $member->shouldReceive('getPaidUntil')
               ->once()
               ->andReturn($date);

        $this->assertTrue($member->isPaid(), 'Failed to assert that 
                             $member->isPaid() returns true when the latest 
                             associated payment is later than today.');
    }

    public function testIfInstanceIsProbablyUpdatedFromRequestObject()
    {
        // Set expected properties
        $expected_name          = "Test Name";
        $expected_birth_place   = "Test City";
        $expected_birth_date    = "1990-01-01";
        $expected_mother_name   = "Mother Name";
        $expected_address       = "12345 Test City, Test Street 678/9";
        $expected_member_since  = "2000-12-31";
        $expected_card_id       = 12345;

        // Create a Request object
        $request = Mockery::mock('Illuminate\Http\Request');
        $request->shouldReceive('has')
                ->times(7)
                ->andReturn(true);
        $request->shouldReceive('get')
                ->with('name')
                ->andReturn($expected_name);
        $request->shouldReceive('get')
                ->with('birth_place')
                ->andReturn($expected_birth_place);
        $request->shouldReceive('get')
                ->with('birth_date')
                ->andReturn($expected_birth_date);
        $request->shouldReceive('get')
                ->with('mother_name')
                ->andReturn($expected_mother_name);
        $request->shouldReceive('get')
                ->with('address')
                ->andReturn($expected_address);
        $request->shouldReceive('get')
                ->with('member_since')
                ->andReturn($expected_member_since);
        $request->shouldReceive('get')
                ->with('card_id')
                ->andReturn($expected_card_id);

        // Update instance from Request
        $member = new Member;
        $member->updateFromRequest($request);

        // Assert that all properties are valid
        $this->assertSame($expected_name, $member->name);
        $this->assertSame($expected_birth_place, $member->birth_place);
        $this->assertSame($expected_birth_date, $member->birth_date);
        $this->assertSame($expected_mother_name, $member->mother_name);
        $this->assertSame($expected_address, $member->address);
        $this->assertSame($expected_member_since, $member->member_since);
        $this->assertSame($expected_card_id, $member->card_id);
    }

    /**
     * Test validation.
     */

    public function testIfNameMustBeAtLeast4Characters()
    {
        $this->member->name = "xxx";

        $this->assertFalse($this->member->validate(null, 
                            ["name", "birth_place", "address", "mother_name"]),
                            'Failed to assert that member name has to be at least 4 characters.');
    }

    public function testIfBirthPlaceMustBeAtLeast2Characters()
    {
        $this->member->birth_place = "x";

        $this->assertFalse($this->member->validate(null, 
                            ["name", "birth_place", "address", "mother_name"]),
                            'Failed to assert that member birth place must be at least 2 characters.');
    }

    public function testIfAddressMustBeAtLeast6Characters()
    {
        $this->member->address = "xxxxx";

        $this->assertFalse($this->member->validate(null, 
                            ["name", "birth_place", "address", "mother_name"]), 'Failed to assert that member
                           address must be at least 6 characters.');
    }

    public function testIfMotherNameMustBeAtLeast4Characters()
    {
        $this->member->mother_name = "xxx";

        $this->assertFalse($this->member->validate(null, 
                            ["name", "birth_place", "address", "mother_name"]),
                            'Failed to assert that member mother name must be at least 4 characters.');
    }

    public function testIfBirthDateMustBeADate()
    {
        $this->member->birth_date = "this is clearly not a date";

        $this->assertFalse($this->member->validate(null, 
                            ["name", "birth_place", "address", "mother_name"]),
                            'Failed to assert that member birth date must be a valid date.');
    }

    public function testIfMemberSinceMustBeADate()
    {
        $this->member->member_since = "this is clearly not a date";

        $this->assertFalse($this->member->validate(null, 
                            ["name", "birth_place", "address", "mother_name"]),
                            'Failed to assert that member join date must be a valid date.');
    }

    public function testIfCardIdMustBeUnique()
    {
        // Save the member with card_id 1 to DB
        $this->member->card_id = 1;
        $this->member->save();

        // Check if it is valid (it should not be valid as card id 1 already
        // exists in the database).
        $this->assertFalse($this->member->validate(null, 
                            ["name", "birth_place", "address", "mother_name"]),
                             'Failed to assert that member card id must be unique.');
    }

    public function testIfCardIdUniqueRuleCanBeIgnored()
    {
        // Save the member with card_id 1 to DB
        $this->member->card_id = 1;
        $this->member->save();

        // Check validation, ignore the unique requirement for the card_id.
        $this->assertTrue($this->member->validate(['card_id'],
                            ["name", "birth_place", "address", "mother_name"]), 
                            'Failed to assert that member card id unique rule can be ignored.');
    }

    /**
     * Test encryption.
     * Since validation is fully transparent, we can only get the encrypted 
     * values using direct queries to a DB.
     */
    

    public function testNameEncryption()
    {
        $this->member->save();

        $name = DB::table('members')
                  ->where('id', '=', $this->member->id)
                  ->pluck('name');

        $this->assertNotSame($this->member->name,
                                $name,
                                'Failed asserting that the name field is stored encrypted in the database.');

        $name = Member::find($this->member->id)->name;

        $this->assertSame($this->member->name, 
                            $name, 
                            'Failed asserting that name field is decrypted on the fly.');  
    }

    public function testAddressEncryption()
    {
        $this->member->save();

        $address = DB::table('members')
                  ->where('id', '=', $this->member->id)
                  ->pluck('address');

        $this->assertNotSame($this->member->address,
                                $address,
                                'Failed asserting that the address field is stored encrypted in the database.');

        $address = Member::find($this->member->id)->address;

        $this->assertSame($this->member->address, 
                            $address, 
                            'Failed asserting that address field is decrypted on the fly.');  
    }

    public function testMotherNameEncryption()
    {
        $this->member->save();

        $mother_name = DB::table('members')
                  ->where('id', '=', $this->member->id)
                  ->pluck('mother_name');

        $this->assertNotSame($this->member->mother_name,
                                $mother_name,
                                'Failed asserting that the mother_name field is stored encrypted in the database.');

        $mother_name = Member::find($this->member->id)->mother_name;

        $this->assertSame($this->member->mother_name, 
                            $mother_name, 
                            'Failed asserting that mother_name field is decrypted on the fly.');  
    }

    public function testBirthPlaceEncryption()
    {
        $this->member->save();

        $birth_place = DB::table('members')
                  ->where('id', '=', $this->member->id)
                  ->pluck('birth_place');
                  
        $this->assertNotSame($this->member->birth_place,
                                $birth_place,
                                'Failed asserting that the birth_place field is stored encrypted in the database.');

        $birth_place = Member::find($this->member->id)->birth_place;

        $this->assertSame($this->member->birth_place, 
                            $birth_place, 
                            'Failed asserting that birth_place field is decrypted on the fly.');  
    }
}
