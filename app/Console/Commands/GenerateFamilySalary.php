<?php

namespace App\Console\Commands;

use App\Models\FamilyMember;
use App\Models\FamilySalary;
use App\Models\FinanceEntry;
use Illuminate\Console\Command;

class GenerateFamilySalary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
     protected $signature = 'salary:generate-family';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate daily salary for family members';

    /**
     * Execute the console command.
     */
      public function handle()
    {
        $today = now()->toDateString();

        $members = FamilyMember::where('is_active', true)->get();

        foreach ($members as $member) {
            // 1. Buat salary harian (hindari dobel)
            $salary = FamilySalary::firstOrCreate(
                ['family_member_id' => $member->id, 'date' => $today],
                ['amount' => $member->daily_salary]
            );

            // 2. Jika salary baru dibuat, masukkan juga ke finance_entries
            if ($salary->wasRecentlyCreated) {
                FinanceEntry::create([
                    'finance_category_id' => 1, // misal kategori Salary ID = 1
                    'direction' => 'expense',
                    'amount' => $member->daily_salary,
                    'note' => "Gaji harian untuk {$member->name} ({$today})",
                ]);
            }
        }

        $this->info("Family salaries + finance entries generated for {$today}");
    }
}
