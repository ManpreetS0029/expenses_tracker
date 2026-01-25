<?php

use App\Models\Credit;
use App\Models\Expense;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $creditExpenses = Expense::where('type', 'credit')->get();

        foreach ($creditExpenses as $expense) {
            Credit::create([
                'user_id' => $expense->user_id,
                'category_id' => $expense->category_id,
                'date' => $expense->date,
                'amount' => $expense->amount,
                'description' => $expense->description,
                'currency' => $expense->currency ?? 'INR',
                'currency_symbol' => $expense->currency_symbol ?? '₹',
            ]);
        }

        Expense::where('type', 'credit')->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $credits = Credit::all();

        foreach ($credits as $credit) {
            Expense::create([
                'user_id' => $credit->user_id,
                'category_id' => $credit->category_id,
                'date' => $credit->date,
                'amount' => $credit->amount,
                'description' => $credit->description,
                'type' => 'credit',
                'classification' => null,
                'currency' => $credit->currency,
                'currency_symbol' => $credit->currency_symbol,
            ]);
        }

        Credit::query()->delete();
    }
};
