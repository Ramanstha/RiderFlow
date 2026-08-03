<?php

namespace App\Console\Commands;

use App\Models\Rider;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class IssueRiderPassword extends Command
{
    protected $signature = 'riders:issue-password
                            {email? : Issue a password to a single rider by email}
                            {--all : Issue passwords to every rider that does not have one}
                            {--password= : Use this password instead of generating one}
                            {--force : Also reset riders who already have a password}';

    protected $description = 'Issue rider portal passwords so riders can sign in at /rider-portal/login';

    public function handle(): int
    {
        $riders = $this->targetRiders();

        if ($riders->isEmpty()) {
            $this->components->info('No riders matched. Every rider already has a password (use --force to reset them).');

            return self::SUCCESS;
        }

        $shared = $this->option('password');

        if ($shared && $riders->count() > 1 && ! $this->confirmSharedPassword($riders->count())) {
            return self::FAILURE;
        }

        $issued = [];

        foreach ($riders as $rider) {
            $password = $shared ?: Str::password(12, symbols: false);

            // The 'hashed' cast on Rider hashes this on save.
            $rider->forceFill(['password' => $password])->save();

            $issued[] = [$rider->name, $rider->email, $password];
        }

        $this->newLine();
        $this->table(['Rider', 'Email', 'Password'], $issued);
        $this->components->info(count($issued).' rider password(s) issued. Riders sign in at /rider-portal/login.');
        $this->components->warn('These passwords are shown once. Copy them now and share them securely.');

        return self::SUCCESS;
    }

    /**
     * @return Collection<int, Rider>
     */
    protected function targetRiders(): Collection
    {
        if ($email = $this->argument('email')) {
            $rider = Rider::query()->where('email', $email)->first();

            if (! $rider) {
                $this->components->error("No rider found with the email [{$email}].");

                return new Collection;
            }

            return new Collection([$rider]);
        }

        if (! $this->option('all')) {
            $this->components->error('Pass a rider email, or --all to cover every rider without a password.');

            return new Collection;
        }

        return Rider::query()
            ->when(! $this->option('force'), fn ($query) => $query->whereNull('password'))
            ->orderBy('name')
            ->get();
    }

    protected function confirmSharedPassword(int $count): bool
    {
        $this->components->warn("You are about to set the same password on {$count} riders.");

        return $this->confirm('Continue?', false);
    }
}
