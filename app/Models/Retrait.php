<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Wallet;
use App\Models\Transaction;

class Retrait extends Model
{
    protected $table = 'retraits';

    protected $fillable = [
        'ecommercant_id',
        'montant',
        'statut',
    ];

    // 🎯 الضربة القاضية: غير الـ Admin يبدل الستاتو لـ valide، السيستيم كينقص الفلوس توماتيكياً مورا الكواليس!
    protected static function boot()
    {
        parent::boot();

        static::updating(function ($retrait) {
            // إيلا كانت الحالة غاتتبدل لـ valide ومكنتش ديجا valide
            if ($retrait->isDirty('statut') && $retrait->statut === 'valide') {
                
                $wallet = Wallet::where('ecommercant_id', $retrait->ecommercant_id)->first();
                
                if ($wallet) {
                    // خصم الفلوس فوراً من الداتابيز
                    $wallet->decrement('solde', $retrait->montant);
                    
                    // تسجيل العملية ف جدول الترانزاكشنز
                    Transaction::create([
                        'wallet_id'   => $wallet->id,
                        'montant'     => $retrait->montant,
                        'type'        => 'debit',
                        'description' => "Retrait sécurisé via Model Hook #{$retrait->id}"
                    ]);
                }
            }
        });
    }

    // --- RELATIONS ---

    // La demande de retrait appartient à un e-commerçant
    public function ecommercant()
    {
        return $this->belongsTo(Utilisateur::class, 'ecommercant_id');
    }
}