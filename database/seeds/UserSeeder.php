<?php

use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class, 50)->create()->each(function (User $user) {
            $user->posts()->save(factory(App\Post::class)->make())
                ->comments()->save(factory(App\Comment::class)->make());
        });
    }
}
