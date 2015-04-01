<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Member;
use App\Payment;
use DB;
use Illuminate\Http\Request;

class PrintableController extends Controller
{
    /**
     * Initializes a new instance of the PrintableController class.
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * GET method, tag-attekintets route (/tag-attekintes)
     * @return Response
     */
    public function getTagAttekintes()
    {
        $members = Member::orderBy('name', 'asc')->get();
        return view('printables.members.overview')->with(['members' => $members]);
    }

    /**
     * GET method, tag-reszletek route (/tag-reszletek/{id})
     * @param  integer $id ID of the member.
     * @return Response
     */
    public function getTagReszletek($id)
    {
        $member = Member::find($id);
        if (is_null($member)) {
            return redirect('/tagok/')->with(['message' => [
                                             'message' => 'Érvénytelen ID',
                                             'success' => 'danger']]);
        }

        return view('printables.members.single')->with(['member' => $member]);
    }

    /**
     * GET method, tagdij-attekintes route (/tagdij-attekintes/)
     * @return Response
     */
    public function getTagdijAttekintes()
    {
        return view('layouts.payments.printsettings');
    }

    /**
     * POST method, tagdij-attekintes route (/tagdij-attekintes/)
     * @return Response
     */
    public function postTagdijAttekintes(Request $req)
    {
        $from = $req->get('from');
        $to = $req->get('to');

        $payments = Payment::whereBetween("paid_at", [new \DateTime($from), new \DateTime($to)])->get();
        $sum = Payment::whereBetween("paid_at", [new \DateTime($from), new \DateTime($to)])->sum('amount');

        return view('printables.payments.overview')->with([
                                                      'from'    => $from,
                                                      'to'      => $to,
                                                      'payments'=> $payments,
                                                      'sum'     => $sum
                                                      ]);
    }
}
