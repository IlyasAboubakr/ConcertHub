<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    
    protected $fillable = ['user_id', 'total_amount', 'status', 'order_date'];

    protected $casts = [
        'order_date' => 'datetime',
    ];

    public function user() {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function orderItems() {
        return $this->hasMany(OrderItem::class);
    }

    public function payment() {
        return $this->hasOne(Payment::class);
    }
}
