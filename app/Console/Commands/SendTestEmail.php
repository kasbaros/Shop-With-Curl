<?php

    namespace App\Console\Commands;

    use Illuminate\Console\Command;
    use Illuminate\Support\Facades\Mail;

    class SendTestEmail extends Command
    {
        // Command signature and description
        protected $signature = 'email:test {email}';
        protected $description = 'Send a test email to the provided address';

        public function __construct()
        {
            parent::__construct();
        }

        public function handle()
        {
            // Get the email from the argument
            $email = $this->argument('email');

            $this->info("Sending test email to: $email");
            
            try {
                Mail::raw('This is a test email from Laravel.', function ($message) use ($email) {
                    $message->to($email)
                        ->subject('Test Email from Laravel');
                });

                $this->info('Test email sent successfully!');
            } catch (\Exception $e) {
                $this->error('Failed to send test email: ' . $e->getMessage());
            }
        }
    }
