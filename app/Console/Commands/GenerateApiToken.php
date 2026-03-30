<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateApiToken extends Command
{
    protected $signature = 'ota:generate-token {--name=ota-cli : Token label}';

    protected $description = 'Generate a Sanctum API token for OTA access';

    public function handle(): int
    {
        $email = env('OTA_ADMIN_EMAIL', 'admin@ota.local');

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => 'OTA Admin',
                'password' => bcrypt(Str::random(32)),
            ]
        );

        $tokenName = $this->option('name');
        $token = $user->createToken($tokenName);

        $this->info('API token created successfully.');
        $this->newLine();
        $this->line($token->plainTextToken);
        $this->newLine();
        $this->warn('Store this token securely — it will not be shown again.');

        return self::SUCCESS;
    }
}
