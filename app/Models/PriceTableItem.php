<?php

namespace App\Models;

use App\Tenant\Traits\TenantTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceTableItem extends Model
{
    use HasFactory;
    use TenantTrait;

    protected $fillable = [
        'tenant_id',
        'price_table_id',
        'service_id',
        'product_id',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function priceTable()
    {
        return $this->belongsTo(PriceTable::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
