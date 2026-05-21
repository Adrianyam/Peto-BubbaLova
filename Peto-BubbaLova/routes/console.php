<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Programar el reporte diario de pedidos
//Schedule::command('report:daily-orders')->everyTenMinutes();

// Alerta de stock bajo
//Schedule::command('report:low-stock')->everyTenMinutes();
