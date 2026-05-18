<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Programar el reporte diario de pedidos
Schedule::command('report:daily-orders')->dailyAt('20:00');

// Alerta de stock bajo a las 11 PM
Schedule::command('report:low-stock')->dailyAt('23:00');
