<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\Wallet;
use Carbon\Carbon;
use Livewire\Attributes\On;

class DashboardStats extends Component
{
    #[On('transaction-saved')]
    #[On('transaction-deleted')] 
    public function render()
    {
        $userId = auth()->id();
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // 1. FLUJO DEL MES
        $monthlyExpenses = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->sum('amount');

        $monthlyIncome = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->sum('amount');

        // 2. GASTOS POR CATEGORÍA (Top 5 del mes)
        $gastosPorCategoria = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->with('category') // Traemos la relación de la categoría
            ->get()
            ->groupBy('category_id')
            ->map(function ($transactions) {
                return [
                    'name' => $transactions->first()->category ? $transactions->first()->category->name : 'Sin Categoría',
                    'total' => $transactions->sum('amount')
                ];
            })
            ->sortByDesc('total')
            ->take(5) // Solo mostramos el Top 5 para no saturar
            ->values();

        // 3. CÁLCULOS DE BILLETERAS
        $liquidez = Wallet::where('user_id', $userId)->where('type', 'standard')->sum('balance');
        $deudaCredito = Wallet::where('user_id', $userId)->where('type', 'credit')->sum('balance');
        $limiteCredito = Wallet::where('user_id', $userId)->where('type', 'credit')->sum('credit_limit');

        $balanceTotal = $liquidez + $deudaCredito; 
        $creditoDisponible = $limiteCredito + $deudaCredito; 

        return view('livewire.dashboard-stats', [
            'monthlyExpenses'   => $monthlyExpenses,
            'monthlyIncome'     => $monthlyIncome,
            'balanceTotal'      => $balanceTotal,
            'liquidez'          => $liquidez,
            'deudaCredito'      => abs($deudaCredito),
            'creditoDisponible' => $creditoDisponible,
            'gastosPorCategoria'=> $gastosPorCategoria, // Mandamos la nueva variable a la vista
        ]);
    }
}