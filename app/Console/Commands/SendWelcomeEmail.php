<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class SendWelcomeEmail extends Command
{
    protected $signature = 'send:welcome-email'; // Command name
    protected $description = 'Send a welcome email to a user'; // Description

    public function handle()
    {
        $user = User::find(1); // Fetch the user
        if ($user) {
            Mail::to($user->email)->send(new WelcomeEmail($user));
            $this->info("Welcome email sent to {$user->email}"); // Provide feedback
        } else {
            $this->warn("User not found"); // Handle user not found
        }
    }
}
