<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Transaction;
use Carbon\Carbon;
use Livewire\Attributes\On;

class DashboardStats extends Component
{
    // Escucha el evento para recalcular cuando guardas un nuevo movimiento
    #[On('transaction-saved')]
    public function render()
    {
        $userId = auth()->id();
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // 1. Gastos del mes actual
        $monthlyExpenses = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->sum('amount');

        // 2. Ingresos del mes actual
        $monthlyIncome = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->sum('amount');

        // 3. Balance Total (Histórico: Todos los ingresos - Todos los gastos)
        $totalIncome = Transaction::where('user_id', $userId)->where('type', 'income')->sum('amount');
        $totalExpense = Transaction::where('user_id', $userId)->where('type', 'expense')->sum('amount');
        $totalBalance = $totalIncome - $totalExpense;

        return view('livewire.dashboard-stats', [
            'monthlyExpenses' => $monthlyExpenses,
            'monthlyIncome' => $monthlyIncome,
            'totalBalance' => $totalBalance,
        ]);
    }
}