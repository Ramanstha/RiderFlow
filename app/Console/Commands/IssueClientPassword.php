<?php

namespace App\Console\Commands;

use App\Models\Company;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class IssueClientPassword extends Command
{
    protected $signature = 'clients:issue-password
                            {email? : Issue a password to a single company by email}
                            {--all : Issue passwords to every company that does not have one}
                            {--password= : Use this password instead of generating one}
                            {--force : Also reset companies who already have a password}';

    protected $description = 'Issue client portal passwords so companies can sign in at /login';

    public function handle(): int
    {
        $companies = $this->targetCompanies();

        if ($companies->isEmpty()) {
            $this->components->info('No companies matched. Every company already has a password (use --force to reset them).');

            return self::SUCCESS;
        }

        $shared = $this->option('password');

        if ($shared && $companies->count() > 1 && ! $this->confirmSharedPassword($companies->count())) {
            return self::FAILURE;
        }

        $issued = [];
        $skipped = [];

        foreach ($companies as $company) {
            // The email is the sign-in name, so a password is useless without one.
            if (blank($company->email)) {
                $skipped[] = $company->name;

                continue;
            }

            $password = $shared ?: Str::password(12, symbols: false);

            // The 'hashed' cast on Company hashes this on save.
            $company->forceFill(['password' => $password])->save();

            $issued[] = [$company->name, $company->email, $password];
        }

        if ($issued) {
            $this->newLine();
            $this->table(['Company', 'Email', 'Password'], $issued);
            $this->components->info(count($issued).' client password(s) issued. Clients sign in at /login.');
            $this->components->warn('These passwords are shown once. Copy them now and share them securely.');
        }

        foreach ($skipped as $name) {
            $this->components->warn("Skipped [{$name}] — it has no email address to sign in with.");
        }

        return self::SUCCESS;
    }

    /**
     * @return Collection<int, Company>
     */
    protected function targetCompanies(): Collection
    {
        if ($email = $this->argument('email')) {
            $company = Company::query()->where('email', $email)->first();

            if (! $company) {
                $this->components->error("No company found with the email [{$email}].");

                return new Collection;
            }

            return new Collection([$company]);
        }

        if (! $this->option('all')) {
            $this->components->error('Pass a company email, or --all to cover every company without a password.');

            return new Collection;
        }

        return Company::query()
            ->when(! $this->option('force'), fn ($query) => $query->whereNull('password'))
            ->orderBy('name')
            ->get();
    }

    protected function confirmSharedPassword(int $count): bool
    {
        $this->components->warn("You are about to set the same password on {$count} companies.");

        return $this->confirm('Continue?', false);
    }
}
