<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class SmtpSetting extends Model
{
    use HasFactory;

    public static function checkSmtpConfig(){
        $setting = self::first();

        Log::info('Fetched SMTP settings:', [
            'smtp_host' => $setting->smtp_host ?? 'Not Found',
            'smtp_username' => $setting->smtp_username ?? 'Not Found',
            'smtp_password' => $setting->smtp_password ?? 'Not Found',
            'smtp_from_name' => $setting->smtp_from_name ?? 'Not Found',
        ]);

        // dd(config('mail.mailers.smtp'));

        if($setting && isset($setting->smtp_host) && isset($setting->smtp_username) && isset($setting->smtp_password))
        {
            config([
                'mail.mailers.smtp.host' => $setting->smtp_host,
                'mail.mailers.smtp.port' => 587,
                'mail.mailers.smtp.username' => $setting->smtp_username,
                'mail.mailers.smtp.password' => $setting->smtp_password,
                'mail.from.address' => $setting->smtp_username,
                'mail.from.name' => $setting->smtp_from_name
                // 'mail.mailers.smtp.host' => env('MAIL_HOST'),
                // 'mail.mailers.smtp.port' => env('MAIL_PORT'),
                // 'mail.mailers.smtp.username' => env('MAIL_USERNAME'),
                // 'mail.mailers.smtp.password' => env('MAIL_PASSWORD'),
                // 'mail.from.address' => env('MAIL_USERNAME'),
                // 'mail.from.name' => 'Shadiya',
                ]);


            return true;
        }else{
            return false;
        }


    }

    public static function setSmtpConfig()
    {
        $setting = self::first();

        if($setting && isset($setting->smtp_host) && isset($setting->smtp_username) && isset($setting->smtp_password))
        {
            config([
                    'mail.mailers.smtp.host' => $setting->smtp_host,
                    'mail.mailers.smtp.port' => 587,
                    'mail.mailers.smtp.username' => $setting->smtp_username,
                    'mail.mailers.smtp.password' => $setting->smtp_password,
                    'mail.from.address' => $setting->smtp_username,
                    'mail.from.name' => $setting->smtp_from_name
                    // 'mail.mailers.smtp.host' => env('MAIL_HOST'),
                    // 'mail.mailers.smtp.port' => env('MAIL_PORT'),
                    // 'mail.mailers.smtp.username' => env('MAIL_USERNAME'),
                    // 'mail.mailers.smtp.password' => env('MAIL_PASSWORD'),
                    // 'mail.from.address' => env('MAIL_USERNAME'),
                    // 'mail.from.name' => 'Shadiya',
                    ]);

            return true;
        }else{
            return false;
        }


    }
}
