<?php

use App\Libraries\FacebookAuthenticator;

/**
 * Basic methods of SammyK's Facebook SDK have fully been tested by the original
 * author and therefore need no additional unit testing.
 * Only additional methods are tested.
 */
class FacebookAuthenticatorTest extends TestCase
{
    public function testIfTokenIsReturned()
    {
        $expected = '1234';

        $fb = Mockery::mock('LaravelFacebookSdk');
        $fb->shouldReceive('getAccessTokenFromRedirect')
           ->once()
           ->andReturn($expected);

        $auth = new FacebookAuthenticator($fb);
        $output = $auth->getTokenFromRedirect();

        $this->assertSame($expected, $output);
    }

    /**
     * @expectedException App\Exceptions\AuthTokenException
     */
    public function testIfExceptionIsThrownWhenNoTokenIsReturned()
    {
        $fb = Mockery::mock('LaravelFacebookSdk');
        $fb->shouldReceive('getAccessTokenFromRedirect')
           ->once()
           ->andReturn(false);

        // Call method. Exepction is expected.
        $auth = new FacebookAuthenticator($fb);
        $auth->getTokenFromRedirect();
    }

    /**
     * @expectedException App\Exceptions\AuthTokenException
     */
    public function testIfCorrectExceptionIsThrownInTokenGetter()
    {
        $fb = Mockery::mock('LaravelFacebookSdk');
        $fb->shouldReceive('getAccessTokenFromRedirect')
           ->once()
           ->andThrow(new Facebook\Exceptions\FacebookSDKException());

        // Call method. Exepction is expected.
        $auth = new FacebookAuthenticator($fb);
        $auth->getTokenFromRedirect();
    }

    public function testExtendingUnextendingToken()
    {
        $expected = '1234';

        $token = Mockery::mock('Token');

        // Not extended yet
        $token->shouldReceive('isLongLived')
              ->once()
              ->andReturn(false);

        // Return string
        $token->shouldReceive('extend')
              ->once()
              ->andReturn($expected);

        // Facebook object should not be accessed
        $auth = new FacebookAuthenticator(null);
        $output = $auth->extendToken($token);

        $this->assertSame($expected, $output);
    }

    public function testExtendingAlreadyExtendedToken()
    {
        $token = Mockery::mock('Token');

        // Not extended yet
        $token->shouldReceive('isLongLived')
              ->once()
              ->andReturn(true);

        // Facebook object should not be accessed
        $auth = new FacebookAuthenticator(null);
        $output = $auth->extendToken($token);

        // The same token is supposed to be returned
        $this->assertSame($token, $output);
    }

    /**
     * @expectedException App\Exceptions\AuthTokenException
     */
    public function testIfCorrectExceptionIsThrownWhenTokenExtensionFails()
    {
        $token = Mockery::mock('Token');

        $token->shouldReceive('isLongLived')
              ->once()
              ->andReturn(false);

        // Return string
        $token->shouldReceive('extend')
              ->once()
              ->andThrow(new Facebook\Exceptions\FacebookSDKException());

        // Facebook object should not be accessed
        $auth = new FacebookAuthenticator(null);
        $output = $auth->extendToken($token);
    }

    public function testIfGraphUserIsReturned()
    {
        $expected = 'Hi I am a graph user object';

        $response = Mockery::mock('FacebookResponse');
        $response->shouldReceive('getGraphUser')
                 ->once()
                 ->andReturn($expected);

        $fb = Mockery::mock('LaravelFacebookSdk');
        $fb->shouldReceive('get')
           ->once()
           ->andReturn($response);

        // Facebook object should not be accessed
        $auth = new FacebookAuthenticator($fb);
        $output = $auth->getGraphUser($response);

        $this->assertSame($expected, $output);
    }

    /**
     * @expectedException App\Exceptions\FacebookRequestException
     */
    public function testIfCorrectExceptionIsThrownInGraphUserGetter()
    {
        $response = Mockery::mock('FacebookResponse');
        $response->shouldReceive('getGraphUser')
                 ->once()
                 ->andThrow(new Facebook\Exceptions\FacebookSDKException());

        $fb = Mockery::mock('LaravelFacebookSdk');
        $fb->shouldReceive('get')
           ->once()
           ->andReturn($response);

        // Facebook object should not be accessed
        $auth = new FacebookAuthenticator($fb);
        $output = $auth->getGraphUser($response);
    }
}
