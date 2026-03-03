<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $fillable = [
        'complaint_number',
        'consumer_name',
        'consumer_document_type',
        'consumer_document_number',
        'consumer_email',
        'consumer_phone',
        'consumer_address',
        'representative_name',
        'representative_email',
        'product_type',
        'product_description',
        'order_number',
        'complaint_type',
        'complaint_detail',
        'consumer_request',
        'provider_response',
        'response_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'response_date' => 'date',
        ];
    }

    public static function generateNumber(): string
    {
        $year = date('Y');
        $last = static::whereYear('created_at', $year)->count() + 1;

        return sprintf('REC-%s-%05d', $year, $last);
    }
}
