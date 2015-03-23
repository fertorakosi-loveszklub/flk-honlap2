<?php
namespace App\Http\Controllers;

use App\Exceptions\AuthTokenException, App\Exceptions\FacebookRequestException;
use App\Http\Controllers\Controller;
use App\Libraries\FacebookAuthenticator;
use App\Libraries\FacebookAuthorizer;
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
        $auth = new FacebookAuthenticator($fb);
        $authorizer = new FacebookAuthorizer($fb);

        try
        {
            // Get Facebook access token from the redirection parameters
            $token = $auth->getTokenFromRedirect();
        }
        catch (AuthTokenException $e)
        {
            // Error
            return redirect('/')->with('message', array(
                'message' => 'Sajnálatos hiba történt, kérlek, próbáld újra.',
                'type' => 'warning'));
        }

        // Extend access token if necessary
        try
        {
            $token = $auth->extendToken($token);
        }
        catch (AuthTokenException $e)
        {
            return redirect('/')->with('message', array(
                'message' => 'Hiba a token meghosszabbításakor.',
                'type' => 'danger'));
        }

        // Set access token
        $fb->setDefaultAccessToken($token);

        //Get user info
        try
        {
            $response = $fb->get('/me?fields=id,name');
            $fbUser = $auth->getGraphUser($response);
        }
        catch (FacebookRequestException $e)
        {
            return redirect('/')->with('message', array(
                'message' => 'Hiba a Facebookkal való kommunikáció közben',
                'type' => 'danger'));
        }

        // Check if user already exists in the local database
        $user = User::find($fbUser['id']);
        if (is_null($user))
        {
            // Doesn't exist yet, create it
            $user = User::createFromGraphObject($fbUser);
        }
        else
        {
            // Already exists, update name
            $user->updateFromGraphObject($fbUser);
        }

        // Save created or updated user
        $user->save();

        // Check admin privileges
        $roles = $authorizer->getUsers($token);
        $isAdmin = $authorizer->is_admin($user['id'], $roles);

        // Only log in activated users.
        if ($isAdmin)
        {
            // Admins are handled as activated
            $user->login();

            // Mark user as admin for the current session
            $authorizer->makeUserAdmin();
        }
        else if ($user->is_activated)
        {
            // User is activated, login
            $user->login();
        }
        else
        {
            // User is not activated, display warning
            return redirect('/')->with('message', array(
                'message' => 'A belépés sikeres, de mielőtt használni kezdhetnéd
                a fiókod, ellenőriznünk kell, valóban klubtag vagy-e.
                Értesítünk, amint ez megtörtént.',
                'type' => 'warning'));
        }

        // Redirect to the main page
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

        // Change name
        $user = Auth::user();
        $user->real_name = $request->input('NewName');

        // Validate
        if (!$user->validate())
        {
            // Return validation error
            $response['message'] = $user->getValidationErrors();
            return response()->json($response);
        }

        // Everything ok, save
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
}
