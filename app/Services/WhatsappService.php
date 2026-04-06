<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappService
{
    public static function send(string $noHp, string $pesan): bool
    {
        $noHp = ltrim($noHp, '+');
        try {
            $response = Http::withHeaders([
                'Authorization' => env('FONNTE_TOKEN'),
            ])->post('https://api.fonnte.com/send', [
                'target'  => $noHp,
                'message' => $pesan,
            ]);

            if (!$response->successful()) {
                Log::warning('Fonnte gagal kirim WA', [
                    'target'   => $noHp,
                    'response' => $response->body(),
                ]);
                return false;
            }

            return true;

        } catch (\Exception $e) {
            Log::error('Fonnte exception: ' . $e->getMessage());
            return false;
        }
    }
}