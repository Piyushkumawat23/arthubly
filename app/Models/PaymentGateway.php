<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    protected $fillable = [
        'name', 'slug', 'logo', 'is_active',
        'mode', 'credentials', 'instructions', 'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'credentials' => 'array',
    ];

    // Sirf active gateways (frontend ke liye)
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    // Ek single credential value nikalne ke liye
    public function credential($key, $default = null)
    {
        return data_get($this->credentials, $key, $default);
    }

    // Config se is gateway ke fields
    public function getFieldsAttribute()
    {
        return config('payment_gateways.'.$this->slug.'.fields', []);
    }
}
