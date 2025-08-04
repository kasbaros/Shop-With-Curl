<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up(): void
        {
            Schema::create('payments', function (Blueprint $table) {
                $table->id();
                $table->uuid('uuid')->unique();
                $table->foreignId('order_id')->constrained()->onDelete('cascade');
                $table->string('payment_method'); // mtn_momo, airtel_money, paypal, stripe, bank_transfer, cod
                $table->string('gateway'); // mtn, airtel, paypal, stripe, manual
                $table->string('status')->default('pending'); // pending, processing, completed, failed, cancelled, refunded
                $table->decimal('amount', 15, 2);
                $table->string('currency', 3)->default('UGX');
                $table->string('transaction_id')->nullable();
                $table->string('gateway_transaction_id')->nullable();
                $table->string('reference')->nullable();
                $table->json('gateway_response')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->timestamp('failed_at')->nullable();
                $table->text('failure_reason')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->index(['status', 'created_at']);
                $table->index(['payment_method', 'status']);
                $table->index('transaction_id');
            });
        }

        public function down(): void
        {
            Schema::dropIfExists('payments');
        }
    };
