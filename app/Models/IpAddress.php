<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IpAddress extends Model
{
    use HasFactory, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ip_address',
        'label'
    ];

    protected static $logAttributes = [
        'ip_address',
        'label'
    ];

    /**
     * The user who owns/created this IP Address
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
