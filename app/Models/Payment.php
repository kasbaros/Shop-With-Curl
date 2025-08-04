<?php

    namespace App\Models;

    use App\Traits\LogsActivity;
    use App\Traits\LogsPayments;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Str;

    class Payment extends Model
    {
        use LogsActivity, LogsPayments;

        protected $fillable = [
            'uuid',
            'order_id',
            'payment_method',
            'gateway',
            'status',
            'amount',
            'currency',
            'transaction_id',
            'gateway_transaction_id',
            'reference',
            'gateway_response',
            'metadata',
            'paid_at',
            'failed_at',
            'failure_reason',
            'notes',
        ];

        protected $casts = [
            'amount' => 'decimal:2',
            'gateway_response' => 'array',
            'metadata' => 'array',
            'paid_at' => 'datetime',
            'failed_at' => 'datetime',
        ];

        protected static function boot()
        {
            parent::boot();

            static::creating(function ($payment) {
                if (!$payment->uuid) {
                    $payment->uuid = Str::uuid();
                }
                if (!$payment->transaction_id) {
                    $payment->transaction_id = 'TXN_' . strtoupper(Str::random(10));
                }
            });

            static::updating(function ($payment) {
                if ($payment->isDirty('status')) {
                    $payment->logStatusChange(
                        $payment->getOriginal('status'),
                        $payment->status
                    );
                }
            });
        }

        public function order()
        {
            return $this->belongsTo(Order::class);
        }

        public function logs()
        {
            return $this->hasMany(PaymentLog::class);
        }

        public function scopePending($query)
        {
            return $query->where('status', 'pending');
        }

        public function scopeCompleted($query)
        {
            return $query->where('status', 'completed');
        }

        public function scopeFailed($query)
        {
            return $query->where('status', 'failed');
        }

        public function isPending()
        {
            return $this->status === 'pending';
        }

        public function isCompleted()
        {
            return $this->status === 'completed';
        }

        public function isFailed()
        {
            return $this->status === 'failed';
        }

        public function markAsCompleted($gatewayTransactionId = null, $gatewayResponse = null)
        {
            $this->update([
                'status' => 'completed',
                'gateway_transaction_id' => $gatewayTransactionId,
                'gateway_response' => $gatewayResponse,
                'paid_at' => now(),
            ]);

            // Update order status
            $this->order->update(['payment_status' => 'paid']);
        }

        public function markAsFailed($reason = null, $gatewayResponse = null)
        {
            $this->update([
                'status' => 'failed',
                'failure_reason' => $reason,
                'gateway_response' => $gatewayResponse,
                'failed_at' => now(),
            ]);
        }

        public function getFormattedAmountAttribute()
        {
            return format_currency($this->amount, $this->currency);
        }
    }
