<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use MongoDB\Laravel\Eloquent\Model;


class User extends Authenticatable implements MustVerifyEmail
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
        'email_verified', 
        'bid_history',
        'lost_objects',
        'admin',
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

    public function getEmailForVerification()
    {
        return $this->email;
    }
    public function sendEmailVerificationNotification()
    {
        $verifyUrl = URL::temporarySignedRoute(
            'verification.verify', now()->addMinutes(60), ['id' => $this->getKey()]
        );

        $this->notify(new EmailVerificationNotification($verifyUrl));
    }

    public function warnIfBidOvertaken($mostRecentBidderName, $mostRecentBidValue, $previousBidValue, $previousBidderName)
    {
        if ($previousBidderName !== null && $mostRecentBidderName !== '0') {
            $this->notify(new BidOvertakenNotification($mostRecentBidderName));
        } elseif ($mostRecentBidderName !== $previousBidderName && $mostRecentBidValue > $previousBidValue) {
            $this->notify(new BidOvertakenNotification($mostRecentBidderName));
        }
    }
    public function createNotificationToken()
    {
        $token = new NotificationToken();
        $token->user_id = $this->id;
        $token->token = \Illuminate\Support\Str::random(60); 
        $token->save();
    }
}
