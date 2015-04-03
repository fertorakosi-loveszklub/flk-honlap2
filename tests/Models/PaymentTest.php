<?php

use App\Payment;


class PaymentTest extends TestCase
{

    public function test_if_instance_is_properly_created_from_request_object()
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
}
