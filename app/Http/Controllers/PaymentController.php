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

    /**
     * GET method, json-importalas route (/json-importalas)
     * @return Response
     */
    public function getJsonImportalas()
    {
        return view('layouts.members.jsoninput');
    }

    /**
     * POST method, json-importalas route (/json-importalas)
     * @return Response
     */
    public function postJsonImportalas(Request $req)
    {
        if (!$req->has('data'))
        {
            return view('layouts.members.jsoninput')->with(['message' =>
                [
                    'message'   => "Hiányzó adat",
                    'type'      => 'danger'
                ]]);
        }

        /**
         * Data should look like this:
         * [
         *      {
         *          "member_id" : 1,
         *          "paid_at"   : "2015-01-01",
         *          "paid_until": "2015-13-31",
         *          "amount"    : 5000
         *      },
         *      {
         *          "member_id" : 1,
         *          "paid_at"   : "2015-01-01",
         *          "paid_until": "2015-13-31",
         *          "amount"    : 5000
         *      }
         * ]
         */
        
        // Add payments
        $data = json_decode($req->get('data'));
        $error = 0;
        $success = 0;

        foreach ($data as $p) {
            $payment = new Payment;
            $payment->member_id  = $p->member_id;
            $payment->paid_at    = $p->paid_at;
            $payment->paid_until = $p->paid_until;
            $payment->amount     = $p->amount;

            // Validate
            if (!$payment->validate()) {
                $error++;
                continue;
            }

            $payment->save();
            $success++;
        }

        return redirect('/tagdij/')->with(['message' => [
                'message'   => "Fizetések könyvelve. $success sikeres, $error hibás adat",
                'type'      => $error > 0 ? "warning" : "success"
            ]]);
    }
}
