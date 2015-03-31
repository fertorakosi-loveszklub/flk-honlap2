<?php namespace App;

use Auth;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;
use Session;

class User extends BaseModel implements Authenticatable
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Hidden fields when serializing to JSON.
     *
     * @var Array
     */
    protected $hidden = ['access_token', 'remember_token'];

    /**
     * Validation rules.
     */
    protected $validationRules = [
        'name'        => 'required|min:4',
        'real_name' => 'required|min:4',
    ];

    /**
     * Value indicating whether to use timestamps in the model.
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Returns the news created by the user.
     *
     * @return Array Array of news created by the user.
     */
    public function news()
    {
        return $this->hasMany('App\News', 'user_id', 'id');
    }

    /**
     * Returns the member linked to this Facebook user.
     *
     * @return App\Member Member linked to this Facebook user.
     */
    public function member()
    {
        return $this->belongsTo('App\Member');
    }

    /**
     * Returns a value indicating whether the user is activated.
     * A user is considered activated when it has a member object associated.
     *
     * @return boolean True if there is a member entity associated with the
     *                 user, otherwise false.
     */
    public function isActivated()
    {
        return !(is_null($this->member));
    }

    /**
     * Gets the Facebook profile picture of the user.
     * @param  LaravelFacebookSdk $fb Facebook SDK object.
     * @return string                 URL of the profile picture.
     */
    public function getProfilePicture(LaravelFacebookSdk $fb)
    {
        // Set app access token
        $fb->setDefaultAccessToken(env('FACEBOOK_APP_ID').'|'
                .env('FACEBOOK_APP_SECRET'));
        
        // Get profile picture
        try {
            $response = $fb->get('/' . $this->id . '/picture?fields=url&redirect=false');
        } catch (Exception $ex) {
            return  '/images/avatar.jpg';
        }

        // Set user access token
        $fb->setDefaultAccessToken(Auth::user()->access_token);

        // Get image from response
        $response = $response->getGraphObject();

        return $response->getProperty('url');
    }

    /**
     * Logs in the current user and sets appropiate session variables.
     */
    public function login()
    {
        // Login, set session variables
        Auth::login($this);
        Session::put('user_full_name', $this->real_name);
        Session::put('member', true);
    }

    /**
     * Updates the name of this user instance from the provided
     * Facebook graph object.
     *
     * @param GraphUser $fbUser Facebook graph object.
     */
    public function updateFromGraphObject($fbUser)
    {
        $this->name = $fbUser['name'];
    }

    /**
     * Creates a model instance from a Facebook graph object.
     *
     * @param $user Facebook User object to create or update in the database.
     */
    public static function createFromGraphObject($fbUser)
    {
        $user = new User();
        $user->id = $fbUser['id'];
        $user->name = $fbUser['name'];
        $user->real_name = $user->name;

        return $user;
    }

    /**
     * Methods of the Illuminate\Contracts\Auth\Authenticatable interface.
     */

    /**
     * Gets the ID used to authenticate.
     *
     * @return string ID used to authenticate.
     */
    public function getAuthIdentifier()
    {
        return $this->id;
    }

    /**
     * Gets the password used to authenticate.
     * As this applciation does provide direct login,
     * this is not necessary and always returns true.
     *
     * @return boolean True.
     */
    public function getAuthPassword()
    {
        return true;
    }

    /**
     * Gets the remember token used to authenticate.
     *
     * @return string The remember token.
     */
    public function getRememberToken()
    {
        return $this->remember_token;
    }

    /**
     * Sets the remember token of the user.
     *
     * @param string $token Remember token.
     */
    public function setRememberToken($token)
    {
        $this->remember_token = $token;
        $this->save();
    }

    /**
     * Gets the name of the remember token.
     * As this applciation does provide direct login,
     * this is not necessary and always returns true.
     *
     * @return boolean True.
     */
    public function getRememberTokenName()
    {
        return true;
    }
}
