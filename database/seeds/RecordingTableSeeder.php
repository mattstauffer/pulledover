<?php

use Illuminate\Database\Seeder;

class RecordingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = factory(\App\User::class, 5)->create(['role' => 42]);

        foreach($users as $user){
            $user->phoneNumbers()->save(factory(\App\PhoneNumber::class, 'verified')->make());
            factory(\App\Recording::class, 5)->create([
                'user_id' => $user->id
            ]);

            if(\Faker\Factory::create()->boolean(75)){
                factory(\App\Recording::class, 'long')->create([
                    'user_id' => $user->id
                ]);
            }
        }
    }
}
