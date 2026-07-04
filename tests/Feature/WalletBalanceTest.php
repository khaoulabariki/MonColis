<?php

namespace Tests\Feature;

use App\Models\Colis;
use App\Models\Utilisateur;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WalletBalanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_wallet_balance_credited_on_livre_and_debited_on_reversal()
    {
        // 1. Create a merchant
        $ecom = Utilisateur::create([
            'nom' => 'Belkhaoua',
            'prenom' => 'Ikram',
            'email' => 'ecom@shipily.ma',
            'password' => bcrypt('password'),
            'role' => 'ecommercant',
            'telephone' => '0667890123',
            'statut' => true,
        ]);

        // Create a livreur
        $livreur = Utilisateur::create([
            'nom' => 'El Alami',
            'prenom' => 'Amine',
            'email' => 'livreur@shipily.ma',
            'password' => bcrypt('password'),
            'role' => 'livreur',
            'telephone' => '0655443322',
            'statut' => true,
        ]);

        // 2. Create a package for this merchant
        $colis = Colis::create([
            'code_suivi' => 'NWS-TEST1234',
            'nom_destinataire' => 'Doe',
            'prenom_destinataire' => 'John',
            'telephone_destinataire' => '0612345678',
            'adresse_destinataire' => '123 Street',
            'poids' => 1.5,
            'prix' => 150.00,
            'statut' => 'enregistre',
            'token_suivi' => 'some-token',
            'ecommercant_id' => $ecom->id,
            'livreur_id' => $livreur->id,
        ]);

        // 3. Act as the livreur and update status to 'livre'
        $response = $this->actingAs($livreur)
            ->put(route('livreur.colis.statut', $colis->id), [
                'statut' => 'livre'
            ]);

        $response->assertStatus(302); // Redirect back

        // 4. Verify wallet and transaction
        $wallet = Wallet::where('ecommercant_id', $ecom->id)->first();
        $this->assertNotNull($wallet);
        // Net: 150.00 - 50 (fraisLivraison) = 100.00
        $this->assertEquals(100.00, $wallet->solde);

        $transaction = Transaction::where('wallet_id', $wallet->id)->first();
        $this->assertNotNull($transaction);
        $this->assertEquals('credit', $transaction->type);
        $this->assertEquals(100.00, $transaction->montant);

        // 5. Now update status from 'livre' back to 'retourne'
        $response2 = $this->actingAs($livreur)
            ->put(route('livreur.colis.statut', $colis->id), [
                'statut' => 'retourne'
            ]);

        $response2->assertStatus(302);

        // 6. Verify wallet balance is decremented back to 0.00 and a debit transaction is logged
        $wallet->refresh();
        $this->assertEquals(0.00, $wallet->solde);

        $debitTransaction = Transaction::where('wallet_id', $wallet->id)
            ->where('type', 'debit')
            ->first();
        $this->assertNotNull($debitTransaction);
        $this->assertEquals(100.00, $debitTransaction->montant);
    }
}
