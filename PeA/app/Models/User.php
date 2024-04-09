<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use MongoDB\Laravel\Eloquent\Model;


class User extends Model implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * 
     *
     * @var array<int, string>
     */

    protected $connection = 'mongodb';
    protected $collection = 'users';
    protected $fillable = [
        'account_id',
        'name',
        'gender',
        'birthdate',
        'address',
        'codigo_postal',
        'localidade',
        'civilId',
        'taxId',
        'contactNumber',
        'email',
        'password',
        'account_status',
        'token',
        'email_verified_at',
        'bid_history',
        'lost_objects',
    ];

    /**
     * 
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'token',
    ];

    /**
     * 
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

        public function hasVerifiedEmail()
    {
        return ! is_null($this->email_verified_at);
    }

    public function markEmailAsVerified()
    {
        $this->email_verified_at = $this->freshTimestamp();
        $this->save();
    }

    public function sendEmailVerificationNotification()
    {
        $verifyUrl = URL::temporarySignedRoute(
            'verification.verify', now()->addMinutes(60), ['id' => $this->getKey()]
        );

        Mail::to($this->email)->send(new VerifyEmailNotification($verifyUrl));
    }

    public function getEmailForVerification()
    {
        return $this->email;
    }
    public function warnIfBidOvertaken($mostRecentBidderName, $mostRecentBidValue, $previousBidValue, $previousBidderName)
    {
        if ($previousBidderName !== null && $mostRecentBidderName !== '0') {
            $this->sendNotification("Your bid has been overtaken by {$mostRecentBidderName}.");
        } elseif ($mostRecentBidderName !== $previousBidderName && $mostRecentBidValue > $previousBidValue) {
            $this->sendNotification("Your bid has been overtaken by {$mostRecentBidderName}.");
        }
    }
}
