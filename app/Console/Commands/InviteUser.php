<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class InviteUser extends Command
{
    protected $signature = 'ota:invite-user
                            {--name= : The name of the user}
                            {--email= : The email of the user}
                            {--password= : The password for the user}';

    protected $description = 'Create a new dashboard user';

    public function handle(): int
    {
        $name = $this->option('name') ?? $this->ask('Name');
        $email = $this->option('email') ?? $this->ask('Email');
        $password = $this->option('password') ?? $this->secret('Password');

        if (User::where('email', $email)->exists()) {
            $this->error("A user with email [{$email}] already exists.");
            return self::FAILURE;
        }

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ]);

        $this->info("User [{$name}] created successfully.");

        return self::SUCCESS;
    }
}
