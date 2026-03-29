<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketType extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = ['event_id', 'name', 'price', 'quantity_available'];

    public function event() {
        return $this->belongsTo(Event::class)->withTrashed();
    }

    public function orderItems() {
        return $this->hasMany(OrderItem::class);
    }
}
