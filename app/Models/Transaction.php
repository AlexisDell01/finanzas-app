<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'user_id', 'type', 'amount', 'date', 'description', 
        'category_id', 'wallet_id', 'to_wallet_id', 'receipt_path'
    ];

    // Para asegurarnos de que la fecha se maneje como un objeto Carbon
    protected $casts = [
        'date' => 'date',
    ];

    // 🌟 AUTOMATIZACIÓN DE SALDOS 🌟
    protected static function booted()
    {
        // 1. CUANDO SE CREA UN MOVIMIENTO: Actualizamos el saldo de la cuenta
        static::created(function ($transaction) {
            $wallet = $transaction->wallet;

            if ($wallet) {
                if ($transaction->type === 'expense') {
                    // Si es gasto, restamos el monto de la cuenta
                    $wallet->decrement('balance', $transaction->amount);
                } 
                elseif ($transaction->type === 'income') {
                    // Si es ingreso, sumamos el monto a la cuenta
                    $wallet->increment('balance', $transaction->amount);
                } 
                elseif ($transaction->type === 'transfer') {
                    // Si es transferencia: quitamos de la origen y sumamos a la destino
                    $wallet->decrement('balance', $transaction->amount);
                    
                    if ($transaction->toWallet) {
                        $transaction->toWallet->increment('balance', $transaction->amount);
                    }
                }
            }
        });

        static::deleted(function ($transaction) {
            $wallet = $transaction->wallet;

            if ($wallet) {
                if ($transaction->type === 'expense') {
                    // Restauramos (sumamos) el dinero que habíamos gastado
                    $wallet->increment('balance', $transaction->amount);
                } 
                elseif ($transaction->type === 'income') {
                    // Restauramos (restamos) el dinero que había entrado por error
                    $wallet->decrement('balance', $transaction->amount);
                } 
                elseif ($transaction->type === 'transfer') {
                    // Devolvemos el dinero a la cuenta origen
                    $wallet->increment('balance', $transaction->amount);
                    
                    // Le quitamos el dinero a la cuenta destino
                    if ($transaction->toWallet) {
                        $transaction->toWallet->decrement('balance', $transaction->amount);
                    }
                }
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    
    public function toWallet(): BelongsTo // Añadí el tipado de retorno por consistencia
    {
        return $this->belongsTo(Wallet::class, 'to_wallet_id');
    }
}