<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendDailyOrdersReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:daily-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía el reporte de pedidos del día al administrador';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Iniciando envío de reporte diario...");

        $today = \Carbon\Carbon::today();
        $date = $today->format('d/m/Y');
        
        $admin = \App\Models\User::role('Administrador')->first();

        if (!$admin) {
            $this->error("No se encontró administrador registrado.");
            return;
        }

        $destinatario = $admin->email;

        $cashierStats = \App\Models\User::role('Cajeros')
            ->withCount(['orders' => function($query) use ($today) {
                $query->whereDate('created_at', $today);
            }])
            ->get();

        $orders = \App\Models\Order::with(['user', 'items.product', 'materials'])
            ->whereDate('created_at', $today)
            ->latest()
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.reports.pedidos-pdf', compact('orders', 'cashierStats', 'date'));
        $pdfContent = $pdf->output();

        try {
            \Illuminate\Support\Facades\Mail::to($destinatario)
                ->send(new \App\Mail\DailyOrdersReport($date, $pdfContent));

            $this->info("Reporte enviado exitosamente a: $destinatario");
        } catch (\Exception $e) {
            $this->error("Error al enviar: " . $e->getMessage());
        }
    }
}
