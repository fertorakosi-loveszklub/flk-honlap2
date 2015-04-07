<?php namespace App\Http\Controllers;

use App\Member;
use App\User;
use Auth;
use DB;
use Illuminate\Http\Request;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;

class MemberController extends Controller
{
    /**
     * Initializes a new instance of the MemberController class.
     */
    public function __construct()
    {
        // Actions that need admin rights
        $this->middleware('admin', ['except' => 'getProfil']);

        // Actions that need login
        $this->middleware('auth', ['only' => ['getProfil']]);
    }

    /**
     * GET method, index route (/).
     *
     * @return Response
     */
    public function getIndex()
    {
        $members = Member::select('id', 'name', 'birth_date', 'card_id')
                            ->orderBy('card_id', 'asc')->get();

        return view('layouts.members.list')->with(['members' => $members,
                                                   'view'    => 'all']);
    }

    /**
     * GET method, json-exportalas route (/json-exportalas)
     * @return Response
     */
    public function getJsonExportalas()
    {
        $members = Member::select('id', 'name', 'birth_date', 'card_id')
                        ->get();

        // Direct json_encode does not work because of the encryption
        $response = array();
        foreach($members as $member) {
            array_push($response, [
                "name"  => $member->name,
                "birth_date" => $member->birth_date,
                "card_id" => $member->card_id,
                "id" => $member->id
            ]);   
        }

        return response()->json($response);
    }

    /**
     * GET method, fizeto route (/fizeto)
     * @return Response
     */
    public function getFizeto()
    {
        $members = Member::whereHas('payments', function ($q) {
            // CURDATE is not supported in all SQL servers (eg. sqlite)
            $today = new \DateTime;
            $today = $today->format('Y-m-d');

            // Filter  paid_untils
            $q->where('paid_until', '>=', $today);
        })->get();

        return view('layouts.members.list')->with(['members' => $members,
                                                  'view'    => 'paid']);
    }

    /**
     * GET method, nem-fizeto route (/nem-fizeto)
     * @return Response
     */
    public function getNemFizeto()
    {
        // CURDATE is not supported in all SQL servers (eg. sqlite)
        $today = new \DateTime;
        $today = $today->format('Y-m-d');

        // Get members with old (invalid)  payments
        $oldPayments = Member
                    ::select('*')
                    ->join('payments', 'payments.member_id', '=', 'members.id')
                    ->leftJoin(DB::raw('payments p2'), function ($join) {
                        $join->on('payments.member_id', '=', 'p2.member_id');
                        $join->on('payments.paid_until', '<', 'p2.paid_until');
                        $join->whereNull('p2.member_id');
                    })
                    ->where('payments.paid_until', '<', $today)
                    ->whereNull('payments.deleted_at')
                    ->groupBy('members.id')
                    ->get();

        // Get members with no payment records
        $noPayments = Member::has('payments', '=', 0)->get();

        // Merge two result sets
        $members = $oldPayments->merge($noPayments);

                 
        return view('layouts.members.list')->with(['members' => $members,
                                                  'view'    => 'notpaid']);
    }
    
    /**
     * GET method, uj route (/uj).
     *
     * @return Response
     */
    public function getUj()
    {
        $nextCardId = Member::max('card_id') + 1;

        return view('layouts.members.editor')->with(['title'      => 'Új tag',
                                                     'nextCardId' => $nextCardId]);
    }

    /**
     * POST method, uj route (/uj).
     *
     * @return Response
     */
    public function postUj(Request $req)
    {
        $member = Member::fromRequest($req);

        if (!$member->validate()) {
            // Validation errors
            return view('layouts.members.editor')
                ->with([
                        'title'     => 'Új tag',
                        'member'    => $member,
                        'error'     => $member->getValidationErrors(),
                    ]);
        } else {
            // Ok, save
            $member->save();

            return redirect('/tagok/')
                ->with([
                        'message' => [
                            'message'  => 'Tag hozzáadva',
                            'type'     => 'success',
                        ],
                    ]);
        }
    }

    /**
     * GET request, szerkesztes route (/szerkesztes/{id}).
     *
     * @param integer $id ID of the user to edit.
     *
     * @return Response
     */
    public function getSzerkesztes($id)
    {
        $member = Member::find($id);

        if (is_null($member)) {
            return redirect('/tagok/')
                    ->with([
                            'message'   => [
                                'message'   => 'Érvénytelen ID',
                                'type'      => 'danger',
                            ],
                        ]);
        }

        return view('layouts.members.editor')
                ->with([
                        'title' => 'Tag szerkesztése',
                        'member' => $member,
                    ]);
    }

    /**
     * POST request, szerkesztes route (/szerkesztes/{id}).
     *
     * @param integer $id ID of the user to edit.
     *
     * @return Response
     */
    public function postSzerkesztes($id, Request $req)
    {
        $member = Member::find($id);

        if (is_null($member)) {
            return redirect('/tagok/')
                    ->with([
                            'message'   => [
                                'message'   => 'Érvénytelen ID',
                                'type'      => 'danger',
                            ],
                        ]);
        }

        $member->updateFromRequest($req);

        if (!$member->validate(['card_id'])) {
            // Validation errors
            return view('layouts.members.editor')
                ->with([
                        'title'     => 'Tag szerkesztése',
                        'member'    => $member,
                        'error'     => $member->getValidationErrors(),
                    ]);
        } else {
            // Ok, save
            $member->save();

            return redirect('/tagok/')
                ->with([
                        'message' => [
                            'message'  => 'Tag frissítve',
                            'type'     => 'success',
                        ],
                    ]);
        }
    }

    /**
     * GET method, torles route (/torles/{id}).
     *
     * @param integer $id ID of the member to delete.
     *
     * @return Response
     */
    public function getTorles($id)
    {
        $member = Member::find($id);

        if (is_null($member)) {
            return redirect('/tagok/')
                    ->with([
                            'message'   => [
                                'message'   => 'Érvénytelen ID',
                                'type'      => 'danger',
                            ],
                        ]);
        }

        // Also, disconnect Facebook user
        $user = User::where('member_id', '=', $id)->first();
        if (!is_null($user)) {
            $user->member_id = null;
            $user->save();
        }

        // Delete member
        $member->delete();

        return redirect('/tagok/')
                    ->with([
                            'message'   => [
                                'message'   => 'Tag sikeresen törölve',
                                'type'      => 'success',
                            ],
                        ]);
    }

    /**
     * GET method, adat-bekeres route (/adat-bekeres).
     *
     * @return Response
     */
    public function getJsonImportalas()
    {
        return view('layouts.members.jsoninput');
    }

    /**
     * POST method, adat-bekeres route (/adat-bekeres).
     *
     * @return Response
     */
    public function postJsonImportalas(Request $req)
    {
        $data = $req->get('data');
        $data = json_decode($data);

        $ok     = 0;
        $error  = 0;

        foreach ($data as $d) {
            $member = Member::fromOldJsonObject($d);
            if ($member->validate()) {
                $member->save();
                $ok++;
            } else {
                $error++;
            }
        }

        $message = "$ok új tag hozzáadva. $error hibás adat.";

        return redirect('/tagok/')->with('message', array(
                'message' => $message,
                'type' => 'success', ));
    }

    /**
     * GET method, felhasznalo-osszekapcsolas route
     * (/felhasznalo-osszekapcsolas/{memberId}).
     *
     * @param integer $id ID of the member.
     *
     * @return Response
     */
    public function getFelhasznaloOsszekapcsolas($memberId, LaravelFacebookSdk $fb)
    {
        $member = Member::find($memberId);

        if (is_null($member)) {
            return redirect('/tagok/')
                    ->with([
                            'message'   => [
                                'message'   => 'Érvénytelen ID',
                                'type'      => 'danger',
                            ],
                        ]);
        }

        $users = User::whereNull('member_id')->get();

        return view('layouts.members.connectable-users')->with([
                'member' => $member,
                'users'  => $users,
                'fb'     => $fb
            ]);
    }

    /**
     * GET method, fb-osszekapcsolas-most route
     * (/fb-osszekapcsolas-most/{member-id}/{user-id})
     * @param  integer $memberId Id of the member.
     * @param  string  $userId   Id of the user.
     * @return  Response
     */
    public function getFbOsszekapcsolasMost($memberId, $userId)
    {
        // Get member
        $member = Member::find($memberId);

        if (is_null($member) || !is_null($member->user)) {
            return redirect('/tagok/')
                    ->with([
                            'message'   => [
                                'message'   => 'Érvénytelen ID',
                                'type'      => 'danger',
                            ],
                        ]);
        }

        // Get user
        $user = User::find($userId);

        if (is_null($user) || !is_null($user->member)) {
            return redirect('/tagok/')
                    ->with([
                            'message'   => [
                                'message'   => 'Érvénytelen ID',
                                'type'      => 'danger',
                            ],
                        ]);
        }

        $user->member_id = $memberId;
        $user->save();


        return redirect('/tagok/')
                ->with([
                        'message'   => [
                            'message'   => 'Felhasználó összekapcsolva.',
                            'type'      => 'success',
                        ],
                    ]);
    }

    /**
     * GET method, szetkapcsolas route (/fb-szetkapcsolas/{memberId})
     * @param  integer $memberId ID of the member.
     * @return Rsponse
     */
    public function getFbSzetkapcsolas($memberId)
    {
        // Get member
        $member = Member::find($memberId);

        if (is_null($member) || is_null($member->user)) {
            return redirect('/tagok/')
                    ->with([
                            'message'   => [
                                'message'   => 'Érvénytelen ID',
                                'type'      => 'danger',
                            ],
                        ]);
        }

        $user = $member->user;
        $user->member_id = null;
        $user->save();

        return redirect('/tagok/')
                ->with([
                        'message'   => [
                            'message'   => 'Fiókok szétkapcsolva.',
                            'type'      => 'success',
                        ],
                    ]);
    }

    /**
     * GET method, profil route (/profil)
     * @return Response
     */
    public function getProfil(LaravelFacebookSdk $fb)
    {
        // Get user and member profile
        $user = Auth::user();
        $member = $user->member;

        return view('layouts.members.own')->with([
                'member' => $member,
                'user'  => $user,
                'fb'     => $fb
            ]);
    }

    /**
     * GET method, tag-osszekapcsolas route (/tag-osszekapcsolas/{user-id})
     * @param  string $userId ID of the user.
     * @return Response
     */
    public function getTagOsszekapcsolas($userId)
    {
        // Get user
        $user = User::find($userId);
        
        if (is_null($user)) {
            return redirect('/tagok/')
                    ->with([
                            'message'   => [

                                'message'   => 'Érvénytelen ID',
                                'type'      => 'danger',
                            ],
                        ]);
        }

        // Get members without a connected profile
        $members = Member::whereNotIn('id', function ($q) {
            $q->whereNotNull('member_id')->select('member_id')->from('users');
        })->get();

        return view('layouts.members.connectable-members')->with([
                'user'    => $user,
                'members' => $members
            ]);
    }

    /**
     * GET method, varolista route (/varolista)
     * @return Response
     */
    public function getVarolista(LaravelFacebookSdk $fb)
    {
        $users = User::whereNull('member_id')->get();

        return view('layouts.members.waitinglist')->with(
            [
                'users' => $users,
                'fb'    => $fb
            ]
        );
    }
}
