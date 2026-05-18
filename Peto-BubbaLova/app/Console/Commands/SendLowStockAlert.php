<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendLowStockAlert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:low-stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Alerta de bajo stock';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Verificando stock...");
        $lowStock = \App\Models\Material::where('quantity', '<=', 20)->get();

        if ($lowStock->isEmpty()) {
            $this->info("Todo en orden. No hay materiales bajo el limite de 20.");
            return;
        }

        $admin = \App\Models\User::role('Administrador')->first();
        if ($admin) {
            \Illuminate\Support\Facades\Mail::to($admin->email)->send(new \App\Mail\LowStockAlert($lowStock));
            $this->info("Alerta enviada exitosamente a: " . $admin->email);
        }
    }
}
