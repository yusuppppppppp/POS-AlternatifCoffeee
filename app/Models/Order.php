<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // Hapus 'customer_name' dari $fillable
    protected $fillable = ['customer_name', 'order_type', 'total_amount', 'cash_paid', 'balance'];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
