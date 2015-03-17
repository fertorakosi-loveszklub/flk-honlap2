<?php 
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Illuminate\Http\Request;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;
use Session;

class AccountController extends BaseController
{
    /**
     * Initializes a new instance of the AccountController class.
     */
    public function __construct() 
    {
        // Actions that need login
        $this->middleware('auth', ['only' => ['postUjNev', 'getKilepes']]);
    }

    /**
     * GET method, belepes route (/belepes)
     * Callback for the facebook login.
     * @return mixed Redirection
     */
    public function getBelepes(LaravelFacebookSdk $fb)
    {
        // Get Facebook access token from the redirection parameters
        try
        {
            $token = $fb->getAccessTokenFromRedirect();

            if (!$token)
            {
                return redirect('/')->with('message', array( 
                    'message' => 'A Facebook belépés sikertelen. Kérlek, próbáld újra.',
                    'type' => 'warning'));
            }
        }
        catch (FacebookQueryBuilderException $e)
        {
            // Error
            return redirect('/')->with('message', array( 
                'message' => $e->getPrevious()->getMessage(),
                'type' => 'danger'));
        }

        // Extend access token if necessary
        if (!$token->isLongLived())
        {
            try
            {
                $token = $token->extend();
            }
            catch (FacebookQueryBuilderException $e)
            {
                return redirect('/')->with('message', array(
                    'message' => $e->getPrevious()->getMessage(),
                    'type' => 'danger'));
            }
        }

        // Set access token
        $fb->setDefaultAccessToken($token);

        //Get user info
        try
        {
            $response = $fb->get('/me?fields=id,name');
            $user = $response->getGraphUser();
        }
        catch (FacebookQueryBuilderException $e)
        {
            return redirect('/')->with('message', array(
                'message' => $e->getPrevious()->getMessage(),
                'type' => 'danger'));
        }
        
        // Check admin privileges
        $isAdmin = $this->checkAdmin($user['id'], $token, $fb);

        // Login user
        $activated = $this->loginUser($user, $isAdmin);

        // Check if activated
        if (!$activated && !$isAdmin) {
            // User not yet activated by an admin
            return redirect('/')->with('message', array( 
                'message' => 'A belépés sikeres, de mielőtt használni kezdhetnéd
                a fiókod, ellenőriznünk kell, valóban klubtag vagy-e. Értesítünk, amint megtörtént.',
                'type' => 'warning'));
        }

        return redirect('/');
    }

    /**
     * POST method, uj-nev route(/uj-nev/)
     * Updates the real name of the currently logged in user.
     * @return mixed JSON
     */
    public function postUjNev(Request $request)
    {
        $response = array(
            'success'   => false,
            'message'   => null,
            'newName'   => null
        );

        // Validate name
        $validation = array(
            'NewName'       => 'required|min:4',
        );

        $this->validate($request, $validation);

        // Change name
        $user = Auth::user();
        $user->real_name = $request->input('NewName');
        $user->save();

        Session::put('user_full_name', $user->real_name);

        $response['success'] = true;
        $response['newName'] = $user->real_name;

        return response()->json($response);
    }

    /**
     * GET method, facebook route (/facebook)
     * @return Redirect to Facebook login
     */
    public function getFacebook(LaravelFacebookSdk $fb)
    {
        return redirect($fb->getLoginUrl());
    }

    /**
     * Logouts a user and destroys its session data.
     * @return Redirection.
     */
    public function getKilepes()
    {
        Session::forget('user_full_name');
        Session::forget('admin');
        Session::forget('member');
        Auth::logout();
        return redirect('/');
    }

    /**
     * Authenticates a user. Checks if it is already in the database,
     * if not, saves it as an inactivated user. If it is, logs it in.
     * @param  User    $user    User object.
     * @param  boolean $isAdmin A value indicating whether the user is an admin.
     *                          Admins will be logged in without activation check.
     * @return boolean          True if user is registered and activated.
     *                           False if not.
     */
    public function loginUser($user, $isAdmin = false)
    {
        $activated = true;

        // Check if user exists
        if (User::find($user['id']) == null) {
            // Does not exist
            $activated = false;
        }

        // Create the user if not exists or update existing
        $user = User::createOrUpdate($user);

        // Check if activated
        $activated = $activated && $user->is_activated;

        // Log in if activated
        if ($activated || $isAdmin) {
            Session::put('user_full_name', $user->real_name);
            Session::put('member', true);
            Auth::login($user);
        }

        // Return
        return $activated || $isAdmin;
    }

    /**
     * Checks if the user is an admin and if it is, it saves it for
     * the current session..
     * @param  string $userId      User id to check.
     * @param  string $tokenBackup Current access token, it will be saved.
     * @param  object $fb          Laravel Facebook SDK instance.
     * @return boolean             True if the user is an admin, otherwise
     *                             false.
     */
    public function checkAdmin($userId, $tokenBackup, LaravelFacebookSdk $fb) 
    {
        try {
            // Sett app access token
            $fb->setDefaultAccessToken(env('FACEBOOK_APP_ID') . '|' . env('FACEBOOK_APP_SECRET'));

            // Get roles
            $response = $fb->get('228336310678604/roles');
            $roles = $response->getGraphList(); 

            // Restore saved token
            $fb->setDefaultAccessToken($tokenBackup);
        }
        catch (Exception $e)
        {
            return false;
        }

        // Loop through roles, check for user id with 'administrators' role
        foreach($roles as $r) {
            if ($userId == $r['user'] && $r['role'] == 'administrators') {
                Session::put('admin', true);
                return true;
            }
        }

        return false;
    }
}
