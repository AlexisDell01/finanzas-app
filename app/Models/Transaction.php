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
       public function toWallet()
    {
        return $this->belongsTo(Wallet::class, 'to_wallet_id');
    }
}