<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Faker\Factory as FakerFactory;
use App\Models\User;

class GenerateRandomUserData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:random-users {count=10}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate random user data';

    /**
     * Faker instance.
     *
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        // Initialize Faker
        $this->faker = FakerFactory::create();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $count = (int) $this->argument('count');

        $this->info("Generating $count random users...");

        for ($i = 0; $i < $count; $i++) {
            User::create([
                'name' => $this->faker->name,
                'email' => $this->faker->unique()->safeEmail,
                'password' => bcrypt('password'),
            ]);
        }

        $this->info("Random users generated successfully.");
    }
}
