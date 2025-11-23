<?php
/**
 * Script untuk Testing Email Configuration
 * 
 * Cara menggunakan:
 * 1. Pastikan file .env sudah dikonfigurasi dengan benar
 * 2. Jalankan: php test-email.php
 * 3. Masukkan email tujuan saat diminta
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Mail;

echo "\n";
echo "========================================\n";
echo "  TEST EMAIL CONFIGURATION\n";
echo "  Alternatif Coffee\n";
echo "========================================\n";
echo "\n";

// Cek konfigurasi
echo "Checking email configuration...\n";
echo "MAIL_MAILER: " . env('MAIL_MAILER', 'not set') . "\n";
echo "MAIL_HOST: " . env('MAIL_HOST', 'not set') . "\n";
echo "MAIL_PORT: " . env('MAIL_PORT', 'not set') . "\n";
echo "MAIL_USERNAME: " . env('MAIL_USERNAME', 'not set') . "\n";
echo "MAIL_FROM_ADDRESS: " . env('MAIL_FROM_ADDRESS', 'not set') . "\n";
echo "MAIL_FROM_NAME: " . env('MAIL_FROM_NAME', 'not set') . "\n";
echo "\n";

// Minta email tujuan
echo "Masukkan email tujuan untuk testing: ";
$email = trim(fgets(STDIN));

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "❌ Email tidak valid!\n";
    exit(1);
}

echo "\n";
echo "Mengirim test email ke: $email\n";
echo "Mohon tunggu...\n\n";

try {
    Mail::raw('Ini adalah test email dari Laravel Alternatif Coffee. Jika Anda menerima email ini, berarti konfigurasi email sudah benar!', function ($message) use ($email) {
        $message->to($email)
                ->subject('Test Email - Alternatif Coffee');
    });
    
    echo "✅ Email berhasil dikirim!\n";
    echo "Silakan cek inbox (dan folder spam) di: $email\n";
    echo "\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "\n";
    echo "Troubleshooting:\n";
    echo "1. Pastikan semua konfigurasi di .env sudah benar\n";
    echo "2. Untuk Gmail, pastikan menggunakan App Password\n";
    echo "3. Jalankan: php artisan config:clear\n";
    echo "4. Cek log: storage/logs/laravel.log\n";
    echo "\n";
    exit(1);
}

echo "========================================\n";
echo "Test selesai!\n";
echo "========================================\n";

