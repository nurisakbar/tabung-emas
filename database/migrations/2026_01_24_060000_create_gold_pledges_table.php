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
        Schema::create('gold_pledges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('gold_amount', 15, 4);
            $table->decimal('price_per_gram', 15, 2);
            $table->decimal('appraisal_value', 15, 2); // Nilai taksiran
            $table->decimal('loan_amount', 15, 2); // Jumlah pinjaman
            $table->decimal('admin_fee', 15, 2)->default(0);
            $table->decimal('storage_fee_rate', 5, 2)->default(1.5); // Persentase per bulan
            $table->decimal('total_amount', 15, 2); // Total yang harus dibayar
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->integer('duration_months');
            $table->integer('extension_count')->default(0);
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['pending', 'active', 'paid', 'overdue', 'auctioned'])->default('pending');
            $table->text('notes')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('approved_at')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('status');
            $table->index('start_date');
            $table->index('end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gold_pledges');
    }
};
