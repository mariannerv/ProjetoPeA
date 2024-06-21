<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Laravel\Sanctum\PersonalAccessToken;
use Carbon\Carbon;

class CleanExpiredTokens extends Command
{
    protected $signature = 'tokens:clean';
    protected $description = 'Apagar tokens que tenham expirado';

    public function handle()
    {
        
        $expiredTokens = PersonalAccessToken::all()->filter(function ($token) {
         
            $carbonExpirationTime = Carbon::parse($token->expires_at);

            return $carbonExpirationTime->isPast();
        });

        foreach ($expiredTokens as $token) {
        
            $token->delete();
        }

        $this->info('Token expirados limpos: ' . $expiredTokens->count());
    }
}
