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
        $faker = \Faker\Factory::create();
        $users = factory(\App\User::class, 5)->create(['role' => 42]);

        foreach($users as $user){
            $user->phoneNumbers()->save(factory(\App\PhoneNumber::class, 'verified')->make());

            $user->friends()->saveMany(factory(\App\Friend::class, $faker->numberBetween(0,3))->make([
                'verified' => $faker->boolean(80)
            ]));

            factory(\App\Recording::class, 5)->create([
                'user_id' => $user->id,
            ]);

            if($faker->boolean(75)){
                factory(\App\Recording::class, 'long')->create([
                    'user_id' => $user->id
                ]);
            }
        }
    }
}
