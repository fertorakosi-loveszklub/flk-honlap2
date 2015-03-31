<?php namespace App;

use DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends BaseModel
{
    use SoftDeletes;

    protected $table = 'members';
    public $timestamps = false;

    /**
     * The validation rules.
     *
     * @var array
     */
    protected $validationRules = [
        'name'          => 'required|min:4',
        'birth_date'    => 'required|date',
        'birth_place'   => 'required:min:2',
        'mother_name'   => 'required|min:4',
        'address'       => 'required|min:6',
        'member_since'  => 'required|date',
        'card_id'       => 'unique:members',
    ];

    /**
     * Returns the Facebook user associated with this member.
     *
     * @return App\User Facebook user associated with this member.
     */
    public function user()
    {
        return $this->hasOne('App\User');
    }

    public function payments()
    {
        return $this->hasMany('App\Payment');
    }

    /**
     * Returns a value indicating whether the member has already paid
     * the membership fee.
     *
     * @return boolean True if the membership fee has been paid,
     *                 otherwise false.
     */
    public function isPaid()
    {
        // Select maximum paid_until date
        $maxPaid = $this->getPaidUntil();

        if (is_null($maxPaid)) {
        // There is no payment
            return false;
        }

        $max = new \DateTime($maxPaid);
        $today = new \DateTime('now');
        return $max >= $today;
    }

    /**
     * Gets the latest paid_until date of the payments of the user.
     * @return DateTime Maximum paid_until date of the users' payments,
     *                  null if there are no payments.
     */
    public function getPaidUntil()
    {
        return DB::table('payments')->where('member_id', '=', $this->id)
                        ->max('paid_until');
    }

    /**
     * Creates a Member object from an old-format JSON object.
     * Deprecated, only used to fill the database with old data.
     *
     * @param object $obj Deserialized JSON object.
     *
     * @return App\Member The created member object.
     */
    public static function fromOldJsonObject($obj)
    {
        $member = new Member();
        $member->name           = $obj->Name;
        $member->birth_date     = $obj->BirthDate;
        $member->birth_place    = $obj->BirthPlace;
        $member->mother_name    = $obj->MotherName;
        $member->address        = $obj->Address;
        $member->member_since   = $obj->MemberSince;
        $member->card_id        = $obj->TagId;

        return $member;
    }

    /**
     * Updates the current object using the values of a request.
     *
     * @param Request $request The request to use as source.
     */
    public function updateFromRequest($request)
    {
        $this->name           = $request->has('name') ? $request->get('name') : '';
        $this->birth_date     = $request->has('birth_date') ? $request->get('birth_date') : '';
        $this->birth_place    = $request->has('birth_place') ? $request->get('birth_place') : '';
        $this->mother_name    = $request->has('mother_name') ? $request->get('mother_name') : '';
        $this->address        = $request->has('address') ? $request->get('address') : '';
        $this->member_since   = $request->has('member_since') ? $request->get('member_since') : '';
        $this->card_id        = $request->has('card_id') ? $request->get('card_id') : '';
    }

    /**
     * Creates a member object from a GET or POST request.
     *
     * @param Request $request The request to use as source.
     *
     * @return App\Member The created member object.
     */
    public static function fromRequest($request)
    {
        $member = new Member();
        $member->updateFromRequest($request);

        return $member;
    }
}
