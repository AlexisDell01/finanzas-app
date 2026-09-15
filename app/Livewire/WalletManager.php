<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Wallet;
use Livewire\Attributes\On; // 🌟 1. Importamos el escuchador de eventos

class WalletManager extends Component
{
    public $name;
    public $balance;
    public $type = 'standard';
    public $credit_limit;

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:standard,credit',
            'credit_limit' => 'nullable|required_if:type,credit|numeric|min:0',
            'balance' => 'nullable|numeric', 
        ]);

        Wallet::create([
            'user_id' => auth()->id(),
            'name' => $this->name,
            'type' => $this->type,
            'credit_limit' => $this->type === 'credit' ? $this->credit_limit : null,
            'balance' => $this->balance ?? 0,
        ]);

        $this->reset(['name', 'balance', 'type', 'credit_limit']);
    }

    public function delete($id)
    {
        Wallet::where('id', $id)->where('user_id', auth()->id())->delete();
    }

    #[On('transaction-saved')] // 🌟 2. Escucha el evento y recarga esta función
    public function render()
    {
        $wallets = Wallet::where('user_id', auth()->id())->latest()->get();
        
        return view('livewire.wallet-manager', [
            'wallets' => $wallets
        ]);
    }
}