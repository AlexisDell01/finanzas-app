<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads; 
use App\Models\Transaction;
use App\Models\Category;
use App\Models\Wallet;
use App\Services\GeminiReceiptService;


class TransactionForm extends Component
{
    use WithFileUploads; 

    public $type = 'expense'; 
    public $amount;
    public $description;
    public $date;
    public $category_id;
    public $wallet_id;

    public $receipt; 
    public $to_wallet_id; 

    public function mount()
    {
        $this->date = now()->format('Y-m-d');
    }
   public function analyzeWithAI(GeminiReceiptService $aiService)
{
    set_time_limit(120);
    $this->validate([
        'receipt' => 'image|max:5120', // Máximo 5MB
    ]);

    // Modificamos aquí: Traemos los gastos globales Y los gastos del usuario
    $categories = Category::where('type', 'expense')
        ->where(function ($query) {
            $query->whereNull('user_id')
                  ->orWhere('user_id', auth()->id());
        })
        ->get();

    try {
        // Llamamos al servicio pasando la ruta temporal del archivo subido
        $data = $aiService->analyzeReceipt($this->receipt->getRealPath(), $categories);

        // ¡La magia sucede aquí! Autocompletamos los campos del formulario
        $this->amount = $data['amount'] ?? $this->amount;
        $this->date = $data['date'] ?? $this->date;
        $this->description = $data['description'] ?? $this->description;
        $this->category_id = $data['category_id'] ?? $this->category_id;
        
    } catch (\Exception $e) {
        $this->addError('receipt', 'No se pudo leer el ticket: ' . $e->getMessage());
    }
}

    public function save()
    {
        $this->validate([
            'type' => 'required|in:income,expense,transfer',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'description' => 'nullable|string|max:255',
            'wallet_id' => 'required|exists:wallets,id',
            // Si es transferencia, exige cuenta destino y que sea diferente a la origen
            'to_wallet_id' => 'nullable|required_if:type,transfer|exists:wallets,id|different:wallet_id',
            // Si NO es transferencia, exige categoría
            'category_id' => 'nullable|required_unless:type,transfer|exists:categories,id',
            'receipt' => 'nullable|image|max:5120',
        ]);

        $receiptPath = null;
        if ($this->receipt) {
            $receiptPath = $this->receipt->store('tickets/' . auth()->id(), 'supabase');
        }

        Transaction::create([
            'user_id' => auth()->id(),
            'type' => $this->type,
            'amount' => $this->amount,
            'date' => $this->date,
            'description' => $this->description,
            'category_id' => $this->type === 'transfer' ? null : $this->category_id,
            'wallet_id' => $this->wallet_id,
            'to_wallet_id' => $this->type === 'transfer' ? $this->to_wallet_id : null,
            'receipt_path' => $receiptPath,
        ]);

        // 🌟 LA MAGIA DE LOS SALDOS 🌟
        $origen = \App\Models\Wallet::find($this->wallet_id);
        
        if ($this->type === 'expense') {
            $origen->decrement('balance', $this->amount);
        } elseif ($this->type === 'income') {
            $origen->increment('balance', $this->amount);
        } elseif ($this->type === 'transfer') {
            $destino = \App\Models\Wallet::find($this->to_wallet_id);
            $origen->decrement('balance', $this->amount); // Sale dinero del origen
            $destino->increment('balance', $this->amount); // Entra a la tarjeta de crédito (paga la deuda)
        }

        $this->reset(['amount', 'description', 'category_id', 'to_wallet_id', 'receipt']);
        
        // Avisamos a las demás tarjetas que se actualicen
        $this->dispatch('transaction-saved'); 
        session()->flash('message', 'Movimiento registrado con éxito.');
    }

    public function render()
    {
        $categories = Category::where('type', $this->type) // Filtramos por ingreso/gasto
                    ->where(function ($query) {
                        $query->whereNull('user_id')
                            ->orWhere('user_id', auth()->id());
                    })
                    ->orderBy('name') // Opcional: para que salgan ordenadas alfabéticamente
                    ->get();
                             
        $wallets = Wallet::where('user_id', auth()->id())->get();

        return view('livewire.transaction-form', [
            'categories' => $categories,
            'wallets' => $wallets,
        ]);
    }
}