<?php

declare(strict_types=1);

use Database\Seeders\DemoSeeder;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command("inspire", function (): void {
    $this->comment(Inspiring::quote());
})->purpose("Display an inspiring quote")->hourly();

Schedule::command("db:seed", ["--class" => DemoSeeder::class, "--force" => true])
    ->dailyAt("04:00")
    ->name("demo-account-reset")
    ->onOneServer();
