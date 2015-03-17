<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Contracts\Auth\Authenticatable;

class User extends Model implements Authenticatable {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * Hidden fields when serializing to JSON.
	 * @var Array
	 */
	protected $hidden = ['access_token', 'remember_token'];

	/**
	 * Value indicating whether to use timestamps in the model.
	 * @var boolean
	 */
	public $timestamps = false;

	/**
	 * Returns the news created by the user.
	 * @return Array Array of news created by the user.
	 */
	public function news() 
	{
		return $this->hasMany('App\News', 'user_id', 'id');
	}

	/**
	 * @param $user Facebook User object to create or update in the database.
     */
	public static function createOrUpdate($fbUser) 
	{
		$dbUser = User::find($fbUser['id']);

		if (is_null($dbUser)) {
			// Not in DB yet, create it
			$user = new User;
			$user->id = $fbUser['id'];
			$user->name = $fbUser['name'];
			$user->real_name = $user->name;

			$user->save();

			return $user;
		} else {
			// User already in DB, update FB name
			$dbUser->name = $fbUser['name'];
			$dbUser->save();

			return $dbUser;
		}
	}

	/**
	 * Methods of the Illuminate\Contracts\Auth\Authenticatable interface
	 */
	
	/**
	 * Gets the ID used to authenticate.
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
	 * @return boolean True.
	 */
	public function getAuthPassword()
	{
		return true;
	}

	/**
	 * Gets the remember token used to authenticate.
	 * @return string The remember token.
	 */
	public function getRememberToken() 
	{
		return $this->remember_token;
	}

	/**
	 * Sets the remember token of the user.
	 * @param string $token Remember token.
	 * @return void
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
	 * @return boolean True.
	 */
	public function getRememberTokenName()
	{
		return true;
	}
}
