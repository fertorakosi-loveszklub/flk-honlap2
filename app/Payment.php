<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends BaseModel
{
    use SoftDeletes;
    
    protected $table = 'payments';
    public $timestamps = false;

    /**
     * The validation rules.
     * @var array
     */
    protected $validationRules = [
        'member_id'  => 'exists:members,id',
        'paid_at'    => 'required|date',
        'paid_until' => 'required|date',
        'amount'     => 'required|integer|min:0'
    ];

    public function member()
    {
        return $this->belongsTo('App\Member');
    }

    /**
     * Creates a Payment from a request.
     * @param  Request $req Request object to get the data from.
     * @return Payment      Craeted payment object.
     */
    public static function fromRequest($req)
    {
        $payment = new Payment;
        $payment->member_id  = $req->has('member_id') ? $req->get('paid_at') : null;
        $payment->paid_at    = $req->has('paid_at') ? $req->get('paid_at') : '';
        $payment->paid_until = $req->has('paid_until') ? $req->get('paid_until') : '';
        $payment->amount     = $req->has('amount') ? $req->get('amount') : '';

        return $payment;
    }
}
