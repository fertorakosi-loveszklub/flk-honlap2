<?php
namespace App\Libraries;

use App\Exceptions\AuthTokenException;
use App\Exceptions\FacebookRequestException;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;

/**
 * Class responsible for auhenticating with Facebook.
 */
class FacebookAuthenticator
{
    /**
     * The Facebook SDK object.
     *
     * @var SammyK\LaravelFacebookSdk\LaravelFacebookSdk;
     */
    private $fb;

    /**
     * Initializes a news instance of the FacebookAuthenticator class.
     *
     * @param SammyK\LaravelFacebookSdk\LaravelFacebookSdk $fb Facebook SDK.
     */
    public function __construct($fb)
    {
        $this->fb = $fb;
    }

    /**
     * Gets the token from the redirection url.
     *
     * @param SammyK\LaravelFacebookSdk\LaravelFacebookSdk $fb Facebook object.
     *
     * @return Token Facebook access token.
     */
    public function getTokenFromRedirect()
    {
        try {
            // Get token
            $token = $this->fb->getAccessTokenFromRedirect();
        } catch (\Exception $e) {
            // Error happened
            throw new AuthTokenException();
        }

        if (!$token) {
            // Token not received
            throw new AuthTokenException();
        }

        return $token;
    }

    /**
     * Exctends a Facebook access token if neccessary.
     *
     * @param Token $token Access token to extend.
     *
     * @return Token Extended token.
     */
    public function extendToken($token)
    {
        if (!$token->isLongLived()) {
            // Not extended yet
            try {
                // Extend
                $token = $token->extend();
            } catch (\Exception $e) {
                // Error happened
                throw new AuthTokenException();
            }

            return $token;
        } else {
            // Already extended
            return $token;
        }
    }

    /**
     * Get the currently graph object of the current user logged in.
     *
     * @return GraphUser Graph user.
     */
    public function getGraphUser($response)
    {
        try {
            // FB->get() is from SammyK's fully unit tested FB SDK
            // No need for separate test
            $response = $this->fb->get('/me?fields=id,name');

            return $response->getGraphUser();
        } catch (\Exception $e) {
            // Error happened
            throw new FacebookRequestException();
        }
    }
}
