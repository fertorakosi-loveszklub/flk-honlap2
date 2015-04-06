<?php

use App\Libraries\FacebookAuthorizer;

class FacebookAuthorizerTest extends TestCase
{
    /**
     * Example admin list object.
     *
     * @var Array of associative arrays.
     */
    private $user_list_example;

    /**
     * Prepare for the tests.
     */
    public function setUp()
    {
        parent::setUp();

        $this->user_list_example =
        [
            [
                'user' => '1234',
                'role' => 'users',
            ],
            [
                'user' => '5678',
                'role' => 'users',
            ],[
                'user' => '9012',
                'role' => 'administrators',
            ],
        ];
    }

    public function testIfUserListIsReturned()
    {
        $response = Mockery::mock('FacebookResponse');
        $response->shouldReceive('getGraphList')
                 ->once()
                 ->andReturn($this->user_list_example);

        $fb = Mockery::mock('LaravelFacebookSdk');
        $fb->shouldReceive('setDefaultAccessToken')
           ->times(2);

        $fb->shouldReceive('get')
           ->once()
           ->andReturn($response);

        $auth = new FacebookAuthorizer($fb);

        // Tokens are irrelevant to this test
        $output = $auth->getUsers(null);

        $this->assertSame($this->user_list_example, $output);
    }

    /**
     * @expectedException App\Exceptions\FacebookRequestException
     */
    public function testIfCorrectExceptionIsThrownInUserListGetter()
    {
        $fb = Mockery::mock('LaravelFacebookSdk');
        $fb->shouldReceive('setDefaultAccessToken')
           ->times(2);

        $fb->shouldReceive('get')
           ->once()
           ->andThrow(new Facebook\Exceptions\FacebookSDKException());

        $auth = new FacebookAuthorizer($fb);

        // Tokens are irrelevant to this test
        $output = $auth->getUsers(null);
    }

    public function testIfTrueIsReturnedWhenUserIsAdmin()
    {
        $admin_id = '9012';

        // Facebook object should not be accessed
        $auth = new FacebookAuthorizer(null);
        $output = $auth->isAdmin($admin_id, $this->user_list_example);

        $this->assertTrue($output, 'Failed asserting that
                          FacebookAuthorizer::isAdmin() returns true when use
                          is an admin.');
    }

    public function testIfFalseIsReturnedWhenUserIsNotAdmin()
    {
        $user_id = '1234';

        // Facebook object should not be accessed
        $auth = new FacebookAuthorizer(null);
        $output = $auth->isAdmin($user_id, $this->user_list_example);

        $this->assertFalse($output, 'Failed asserting that
                           FacebookAuthorizer::isAdmin() returns false when user
                           is not an admin.');
    }

    public function testIfFalseIsReturnedWhenUserDoesNotExist()
    {
        $user_id = 'I don\'t exist';

        // Facebook object should not be accessed
        $auth = new FacebookAuthorizer(null);
        $output = $auth->isAdmin($user_id, $this->user_list_example);

        $this->assertFalse($output, 'Failed asserting that
                           FacebookAuthorizer::isAdmin() returns false when user
                           does not exist.');
    }

    public function testIfUserIsMarkedIsAdmin()
    {
        // Facebook object should not be accessed
        $auth = new FacebookAuthorizer(null);
        $auth->makeUserAdmin();

        $this->assertSessionHas('admin', true);
    }
}
