<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class, 10)->create()->each(function ($user) {
            factory(App\Report::class, mt_rand(1, 20))->make()->each(function($report) use ($user){
                $user->reports()->save($report);
            });

        });
    }
}
