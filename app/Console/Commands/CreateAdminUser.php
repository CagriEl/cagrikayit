<?php

namespace App\Console\Commands;

use App\Enums\Rol;
use App\Models\User;
use Illuminate\Console\Command;

class CreateAdminUser extends Command
{
    protected $signature = 'admin:create
                            {--name=Admin : Yönetici adı}
                            {--email= : Yönetici e-posta adresi}
                            {--password= : Yönetici şifresi}';

    protected $description = 'Yönetim paneli için admin kullanıcısı oluşturur';

    public function handle(): int
    {
        $email = $this->option('email');
        $password = $this->option('password');
        $name = $this->option('name') ?: 'Admin';

        if (blank($email) || blank($password)) {
            $this->error('Email ve şifre zorunludur.');
            $this->line('Örnek: php artisan admin:create --name="Admin" --email="admin@firma.com" --password="GucluSifre123"');

            return self::FAILURE;
        }

        if (strlen($password) < 8) {
            $this->error('Şifre en az 8 karakter olmalıdır.');

            return self::FAILURE;
        }

        $user = User::query()->updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => $password,
                'is_admin' => true,
                'rol' => Rol::Personel,
            ],
        );

        $this->info("Admin kullanıcısı hazır: {$user->email}");
        $this->line('Giriş: /admin');

        return self::SUCCESS;
    }
}
