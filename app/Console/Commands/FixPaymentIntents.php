<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Purchase;

class FixPaymentIntents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:payment-intents';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Corrige los payment_intent_id que tienen JSON completo';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 Corrigiendo payment_intent_id en la base de datos...');
        $this->newLine();

        $purchases = Purchase::all();
        $fixed = 0;

        foreach ($purchases as $purchase) {
            $paymentIntentId = $purchase->payment_intent_id;
            
            // Si contiene JSON o el objeto completo
            if (strpos($paymentIntentId, 'Stripe\\PaymentIntent') === 0 || strpos($paymentIntentId, '{') === 0) {
                // Extraer el ID usando regex
                preg_match('/"id":\s*"(pi_[^"]+)"/', $paymentIntentId, $matches);
                
                if (isset($matches[1])) {
                    $newId = $matches[1];
                    $purchase->payment_intent_id = $newId;
                    $purchase->save();
                    
                    $this->info("✅ Compra #{$purchase->id}: {$newId}");
                    $fixed++;
                } else {
                    $this->error("❌ Compra #{$purchase->id}: No se pudo extraer el ID");
                }
            } else if (strpos($paymentIntentId, 'pi_') === 0) {
                $this->line("✓ Compra #{$purchase->id}: Ya está correcto ({$paymentIntentId})");
            }
        }

        $this->newLine();
        $this->info("🎉 Proceso completado. Registros corregidos: {$fixed}");
        
        return 0;
    }
}
