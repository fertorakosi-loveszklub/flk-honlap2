<?php
namespace App\Libraries;

use App\Exceptions\AuthTokenException;
use App\Exceptions\FacebookRequestException;

use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;
use Session;

/**
 * Class responsible for authorization with Facebook.
 */
class FacebookAuthorizer
{
    /**
     * The Facebook SDK object.
     * @var SammyK\LaravelFacebookSdk\LaravelFacebookSdk;
     */
    private $fb;

    /**
     * Initializes a news instance of the FacebookAuthenticator class.
     * @param SammyK\LaravelFacebookSdk\LaravelFacebookSdk $fb Facebook SDK.
     */
    public function __construct($fb)
    {
        $this->fb = $fb;
    }

    /**
     * Checks if the user with the given ID has admin rights.
     * @param  integer   $user_id    User ID.
     * @param  GraphList $roles      List of all app users and their roles.
     * @return boolean               A value indicating whether the user with
     *                               the given ID is an admin.
     */
    public function is_admin($user_id, $roles)
    {
        // Loop through roles, check for user id with 'administrators' role
        foreach($roles as $r) {
            if ($user_id == $r['user'] && $r['role'] == 'administrators') {
                return true;
            }
        }

        return false;
    }

    /**
     * Gets the list of all users in the current application and their roles.
     * @param  Token $current_token  Current token to be restored after the
     *                               request.
     * @return GraphList             List of users in the current application.
     */
    public function getUsers($current_token)
    {
        try {
            // Set app access token
            $this->fb->setDefaultAccessToken(env('FACEBOOK_APP_ID') . '|'
                . env('FACEBOOK_APP_SECRET'));

            // Get roles
            $response = $this->fb->get('228336310678604/roles');
            $users = $response->getGraphList();

            // Restore saved token
            $this->fb->setDefaultAccessToken($current_token);
        }
        catch (\Exception $e)
        {
            throw new FacebookRequestException;
        }

        return $users;
    }

    /**
     * Sets the appropiate session variables of the current user.
     * The user will be an admin for the current session.
     */
    public function makeUserAdmin()
    {
        Session::set('admin', true);
    }
}
