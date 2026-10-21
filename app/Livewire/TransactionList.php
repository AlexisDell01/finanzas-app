<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\Wallet;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Storage;

class TransactionList extends Component
{
    #[On('transaction-saved')]
    public function render()
    {
        $transactions = Transaction::with(['category', 'wallet', 'toWallet'])
            ->where('user_id', auth()->id())
            ->latest('date') // Ordenamos por fecha
            ->take(20)
            ->get();

        return view('livewire.transaction-list', [
            'transactions' => $transactions
        ]);
    }

    // 🌟 NUEVA FUNCIÓN PARA ELIMINAR 🌟
    public function delete($id)
    {
        // 1. Buscamos el movimiento
        $transaction = Transaction::where('id', $id)->where('user_id', auth()->id())->first();

        if ($transaction) {
            // Buscamos la cuenta origen (puede que ya la hayas borrado antes)
            $origen = Wallet::find($transaction->wallet_id);

            // 2. Aplicamos la matemática INVERSA solo si las cuentas AÚN EXISTEN
            if ($transaction->type === 'expense') {
                if ($origen) {
                    $origen->increment('balance', $transaction->amount);
                }
            } elseif ($transaction->type === 'income') {
                if ($origen) {
                    $origen->decrement('balance', $transaction->amount);
                }
            } elseif ($transaction->type === 'transfer') {
                $destino = Wallet::find($transaction->to_wallet_id);
                
                if ($origen) {
                    $origen->increment('balance', $transaction->amount);
                }
                if ($destino) {
                    $destino->decrement('balance', $transaction->amount);
                }
            }

            $transaction->delete();
            

            $this->dispatch('transaction-saved');
        }
    }
    public function downloadReceipt($id)
        {
            // Buscamos el movimiento
            $transaction = Transaction::where('id', $id)->where('user_id', auth()->id())->first();

            if ($transaction && $transaction->receipt_path) {
                // Extraemos la extensión original (ej. png, jpg)
                $extension = pathinfo($transaction->receipt_path, PATHINFO_EXTENSION);
                
                // Creamos un nombre bonito para el archivo que se va a descargar
                $fileName = 'Ticket_' . $transaction->date . '_' . $transaction->category?->name . '.' . $extension;

                // Le decimos a Livewire que fuerce la descarga del archivo desde Supabase
                return Storage::disk('supabase')->download($transaction->receipt_path, $fileName);
            }
        }
    
}