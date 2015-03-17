<?php

use App\RecordCategory;
use App\Page;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		// Seed record categories table
		$this->call('RecordCategoriesSeeder');

        // Seed pages table
		$this->call('PagesSeeder');
	}
}


class RecordCategoriesSeeder extends Seeder {

    public function run()
    {
        DB::table('record_categories')->delete();

        RecordCategory::create(array('title' => 'Légpuska 10m'));
        RecordCategory::create(array('title' => 'Légpisztoly 10m'));
        RecordCategory::create(array('title' => 'Sportpisztoly 25m'));
        RecordCategory::create(array('title' => 'Kispuska 50m'));
    }

}

class PagesSeeder extends Seeder {

    public function run()
    {
        DB::table('pages')->delete();

        Page::create(array('id' => 'tortenet', 'title' => 'A Klub története', 'content' => '...'));
        Page::create(array('id' => 'edzesek', 'title' => 'Edzések', 'content' => '...'));
        Page::create(array('id' => 'arak', 'title' => 'Árak', 'content' => '...'));
        Page::create(array('id' => 'elerhetosegek', 'title' => 'Elérhetőségek', 'content' => '...'));
    }

}