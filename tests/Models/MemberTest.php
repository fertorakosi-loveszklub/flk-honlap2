<?php

use App\Member;


class MemberTest extends TestCase
{    
    public function test_if_ispaid_is_false_when_payment_is_null()
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

    public function test_if_ispaid_is_false_when_latest_payment_is_earlier_than_today()
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

    public function test_if_ispaid_is_true_when_latest_payment_is_today()
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

    public function test_if_ispaid_is_true_when_latest_payment_is_later_than_today()
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

    public function test_if_instance_is_properly_updated_from_request_object()
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
}
