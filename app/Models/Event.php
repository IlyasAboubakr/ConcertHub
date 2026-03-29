<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'title',
        'description',
        'artist_name',
        'location',
        'event_date',
        'event_time',
        'image',
        'organizer_id',
        'status'
    ];

    protected $casts = [
        'event_date' => 'date',
    ];

    public function organizer() {
        return $this->belongsTo(User::class, 'organizer_id')->withTrashed();
    }

    public function ticketTypes() {
        return $this->hasMany(TicketType::class);
    }

    protected static function booted()
    {
        static::deleting(function ($event) {
            $event->ticketTypes()->delete();
        });

        static::restoring(function ($event) {
            $event->ticketTypes()->withTrashed()->restore();
        });
    }
}
