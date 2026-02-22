<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Exception;

class EmailTestController extends Controller
{
    public function test()
    {
        try {
            Mail::raw('This is a test email from Citation Hub to verify SMTP settings on Render.', function ($message) {
                $message->to(config('mail.from.address'))
                        ->subject('Email Diagnostic Test');
            });

            return response()->json([
                'success' => true,
                'message' => 'Email sent successfully! Check ' . config('mail.from.address'),
                'config' => [
                    'host' => config('mail.mailers.smtp.host'),
                    'port' => config('mail.mailers.smtp.port'),
                    'encryption' => config('mail.mailers.smtp.encryption'),
                    'from' => config('mail.from.address'),
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Email failed: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'config' => [
                    'host' => config('mail.mailers.smtp.host'),
                    'port' => config('mail.mailers.smtp.port'),
                    'encryption' => config('mail.mailers.smtp.encryption'),
                    'from' => config('mail.from.address'),
                ]
            ], 500);
        }
    }
}
