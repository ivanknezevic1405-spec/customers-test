<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create {--name=} {--email=} {--password=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an admin user for Filament';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->option('name') ?? env('ADMIN_NAME') ?? $this->ask('Admin name', 'Admin User');
        $email = $this->option('email') ?? env('ADMIN_EMAIL') ?? $this->ask('Admin email', 'admin@example.com');
        $password = $this->option('password') ?? env('ADMIN_PASSWORD') ?? $this->secret('Admin password');

        if (User::where('email', $email)->exists()) {
            $this->error("User with email {$email} already exists!");
            return Command::FAILURE;
        }

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'email_verified_at' => now(),
        ]);

        $this->info("Admin user created successfully!");
        $this->table(
            ['Name', 'Email'],
            [[$name, $email]]
        );

        return Command::SUCCESS;
    }
}
