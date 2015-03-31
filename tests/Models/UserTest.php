<?php

use App\User;

class UserTest extends TestCase
{
    public function test_if_login_is_successful()
    {
        $user = new User();
        $user->login();

        // Assert session has a member key
        $this->assertSessionHas('member', true);

        // Assert user is logged in
        $this->assertTrue(Auth::check(), 'Failed asserting that user is logged
            in after calling User::login()');
    }

    public function test_if_user_name_is_saved_to_session_after_login()
    {
        $expected = 'Test Name';

        $user = new User();
        $user->real_name = $expected;
        $user->login();

        $this->assertSessionHas('user_full_name', $expected);
    }

    public function test_if_the_user_logged_in_is_the_current_user()
    {
        $expected = '1234';

        $user = new User();
        $user->id = $expected;
        $user->login();

        $this->assertSame(Auth::user()->id, $expected);
    }

    public function test_if_user_is_created_from_graph_object()
    {
        $expected = '1234';

        $fbUser = [
            "id"    => $expected,
            "name"  => "Test name",
        ];

        $user = User::createFromGraphObject($fbUser);

        $this->assertSame($expected, $user->id);
    }

    public function test_if_created_user_object_has_the_same_name_as_the_graph_object()
    {
        $expected = 'Test Name';

        $fbUser = [
            'id'    => '1234',
            'name'  => $expected,
        ];

        $user = User::createFromGraphObject($fbUser);

        $this->assertSame($expected, $user->name);
    }

    public function test_if_name_is_updated_from_graph_object()
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

    /**
     * Validation tests.
     */
    public function test_if_name_must_be_at_least_4_characters()
    {
        $user = new User();
        $user->real_name = 'Test name';
        $user->name = 'xxx';

        $this->assertFalse($user->validate(), 'Failed asserting that the name must be at least 4 characters.');
    }

    public function test_if_real_name_must_be_at_least_4_characters()
    {
        $user = new User();
        $user->name = 'Test name';
        $user->real_name = 'xxx';

        $this->assertFalse($user->validate(), 'Failed asserting that the real name must be at least 4 characters.');
    }

    public function test_if_new_user_is_not_activated()
    {
        $user = Mockery::mock('App\User[getAttribute]');
        $user->shouldReceive('getAttribute')
             ->with('member')
             ->once()
             ->andReturn(null);

        $this->assertFalse($user->isActivated(), 'Failed asserting that a new user is inactive by default.');
    }
}
