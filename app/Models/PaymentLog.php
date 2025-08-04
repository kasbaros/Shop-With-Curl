<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;

    class PaymentLog extends Model
    {
        protected $fillable = [
            'payment_id',
            'type',
            'gateway',
            'data',
            'status',
        ];

        protected $casts = [
            'data' => 'array',
        ];

        public function payment()
        {
            return $this->belongsTo(Payment::class);
        }
    }
