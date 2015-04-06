<?php

use App\Payment;

class PaymentTest extends TestCase
{
    private $payment;

    public function setUp()
    {
        parent::setUp();

        Artisan::call('migrate');
        Artisan::call('db:seed');

        // Save random member to test if member_id must exist in the database
        $member = new \App\Member;
        $member->name = "xxx";
        $member->birth_date = "1990-01-01";
        $member->birth_place = "xxx";
        $member->address = "xxx";
        $member->mother_name = "xxx";
        $member->member_since = "1991-01-01";
        $member->save();

        $this->payment = new Payment;
        $this->payment->member_id   = $member->id;
        $this->payment->paid_at     = "2000-01-01";
        $this->payment->paid_until  = "2001-01-01";
        $this->payment->amount      = 5000;

        $this->assertTrue($this->payment->validate(), 'Test object is invalid,
                          make sure everything is correct');
    }

    public function testIfInstanceIsProperlyCreatedFromRequestObject()
    {
        // Set expected properties
        $expected_member_id     = 0;
        $expected_paid_at       = "2000-01-01";
        $expected_paid_until    = "2000-12-31";
        $expected_amount        = 1000;

        // Create a Request object
        $request = Mockery::mock('Illuminate\Http\Request');
        $request->shouldReceive('has')
                ->times(4)
                ->andReturn(true);

        $request->shouldReceive('get')
                ->with('member_id')
                ->andReturn($expected_member_id);

        $request->shouldReceive('get')
                ->with('paid_at')
                ->andReturn($expected_paid_at);

        $request->shouldReceive('get')
                ->with('paid_until')
                ->andReturn($expected_paid_until);

        $request->shouldReceive('get')
                ->with('amount')
                ->andReturn($expected_amount);

        // Create instance from Request
        $payment = Payment::fromRequest($request);

        // Assert that all properties are valid
        $this->assertSame($expected_member_id, $payment->member_id);
        $this->assertSame($expected_paid_at, $payment->paid_at);
        $this->assertSame($expected_paid_until, $payment->paid_until);
        $this->assertSame($expected_amount, $payment->amount);
    }

    public function testIfMemberIdHasToExistInTheDatabase()
    {
        $this->payment->member_id++;

        $this->assertFalse($this->payment->validate(), 'Failed to assert that 
                           payment member id must exist in the database.');
    }

    public function testIfPaidAtMustBeADate()
    {
        $this->payment->paid_at = "Definitely not a date.";

        $this->assertFalse($this->payment->validate(), 'Failed to assert that
                           payment date must be a valid date.');
    }

    public function testIfPaidUntilMustBeADate()
    {
        $this->payment->paid_until = "Definitely not a date";

        $this->assertFalse($this->payment->validate(), 'Failed to assert that 
                           "paid until" must be a valid date.');
    }

    public function testIfAmountMustBeAtLeast0()
    {
        $this->payment->amount = -1;

        $this->assertFalse($this->payment->validate(), 'Failed to assert that
                           amount must be at least 0.');
    }
}
