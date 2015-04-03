<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Member;
use App\Payment;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Initializes a new instance of PaymentController.
     **/
    public function __construct()
    {
        // Admin rights are required
        $this->middleware('admin');
    }
    
    /**
     * GET method, index route (/)
     * @return Response
     */
    public function getIndex()
    {
        // Get payments
        $payments = Payment::with('member')->get();

        return view('layouts.payments.list')->with(['payments' => $payments]);
    }

    /**
     * GET method, fizetes route (/fizetes)
     * @param  integer $id ID of the paying member.
     * @return Response
     */
    public function getFizetes($id)
    {
        // Get member
        $member = Member::find($id);
        
        if (is_null($member)) {
            // Get member
            return redirect('/tagdij/')
                        ->with([ 'message' => [
                                    'message'   => 'Érvénytelen ID',
                                    'type'      => 'danger'
                               ]]);
        }

        return view('layouts.payments.new')->with(['member' => $member]);
    }

    /**
     * POST method, fizetes route (/fizetes/{id})
     *
     * @param integer ID of the user to book the payment for.
     * @return  Redirect
     */
    public function postFizetes($id, Request $req)
    {
        // Get member
        $member = Member::find($id);
        
        if (is_null($member)) {
            // Get member
            return redirect('/tagdij/')
                        ->with([ 'message' => [
                                    'message'   => 'Érvénytelen ID',
                                    'type'      => 'danger'
                               ]]);
        }

        // Book payment
        $payment = Payment::fromRequest($req);
        $payment->member_id = $id;

        if (!$payment->validate()) {
            return redirect('/tagdij/fizetes/' . $id)
                        ->with(['message' => [
                                    'message'   => 'Hibás adatok',
                                    'type'      => 'danger'
                               ]]);
        }

        $payment->save();

        return redirect('/tagok/')->with(['message' => [
                                            'message' => 'Tagdíj-fizetés elkönyvelve',
                                            'type'    => 'success'
                                         ]]);
    }

    /**
     * GET method, torles route (/torles/[id])
     * @param  integer $id ID of the payment to delete.
     * @return Response
     */
    public function getTorles($id)
    {
        $payment = Payment::find($id);

        if (is_null($payment)) {
            // Get member
            return redirect('/tagdij/')
                        ->with([ 'message' => [
                                    'message'   => 'Érvénytelen ID',
                                    'type'      => 'danger'
                               ]]);
        }

        $payment->delete();

        return redirect('/tagdij')->with([
                                         'message' => [
                                            'message' => 'Fizetés törölve',
                                            'type'    => 'success'
                                         ]]);
    }
}
