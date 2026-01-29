<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('installment_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('installment_plan_id')->constrained('gold_installment_plans')->onDelete('cascade');
            $table->foreignId('schedule_id')->nullable()->constrained('installment_schedules')->onDelete('set null');
            $table->decimal('payment_amount', 15, 2);
            $table->date('payment_date');
            $table->enum('payment_method', ['transfer', 'cash', 'other'])->default('transfer');
            $table->string('reference_number')->nullable();
            $table->decimal('allocated_gold', 15, 4)->default(0);
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('verified_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('installment_plan_id');
            $table->index('payment_date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('installment_payments');
    }
};
