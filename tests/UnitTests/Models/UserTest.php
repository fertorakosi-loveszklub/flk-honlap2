<?php

use App\User;

class UserTest extends TestCase
{
    public function testIfLoginIsSuccessful()
    {
        $user = new User();
        $user->login();

        // Assert session has a member key
        $this->assertSessionHas('member', true);

        // Assert user is logged in
        $this->assertTrue(Auth::check(), 'Failed asserting that user is logged
            in after calling User::login()');
    }

    public function testIfNameIsSavedToSessionAfterLogin()
    {
        $expected = 'Test Name';

        $user = new User();
        $user->real_name = $expected;
        $user->login();

        $this->assertSessionHas('user_full_name', $expected);
    }

    public function testIfUserLoggedInIsCurrentUser()
    {
        $expected = '1234';

        $user = new User();
        $user->id = $expected;
        $user->login();

        $this->assertSame(Auth::user()->id, $expected);
    }

    public function testIfUserIsCreatedFromGraphObject()
    {
        $expected = '1234';

        $fbUser = [
            "id"    => $expected,
            "name"  => "Test name",
        ];

        $user = User::createFromGraphObject($fbUser);

        $this->assertSame($expected, $user->id);
    }

    public function testIfCreatedUserHasSameName()
    {
        $expected = 'Test Name';

        $fbUser = [
            'id'    => '1234',
            'name'  => $expected,
        ];

        $user = User::createFromGraphObject($fbUser);

        $this->assertSame($expected, $user->name);
    }

    public function testIfNameIsUpdatedFromGraphObject()
    {
        $expected = 'New name';

        $fbUser = [
            'id'    => '1234',
            'name'  => $expected,
        ];

        $user = new User();
        $user->name = 'Old name';
        $user->updateFromGraphObject($fbUser);

        $this->assertSame($expected, $user->name);
    }

    public function testIfNameMustBeAtLeast4Characters()
    {
        $user = new User();
        $user->real_name = 'Test name';
        $user->name = 'xxx';

        $this->assertFalse($user->validate(), 'Failed asserting that the name
                           must be at least 4 characters.');
    }

    public function testIfRealNameMustBeAtLEast4Characters()
    {
        $user = new User();
        $user->name = 'Test name';
        $user->real_name = 'xxx';

        $this->assertFalse($user->validate(), 'Failed asserting that the real
                           name must be at least 4 characters.');
    }

    public function testIfNewUserIsInactive()
    {
        $user = Mockery::mock('App\User[getAttribute]');
        $user->shouldReceive('getAttribute')
             ->with('member')
             ->once()
             ->andReturn(null);

        $this->assertFalse($user->isActivated(), 'Failed asserting that a new
                           user is inactive by default.');
    }
}
