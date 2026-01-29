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
        Schema::create('pledge_extensions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pledge_id')->constrained('gold_pledges')->onDelete('cascade');
            $table->integer('extension_duration'); // Berapa bulan diperpanjang
            $table->decimal('extension_fee', 15, 2);
            $table->date('new_end_date');
            $table->enum('status', ['pending', 'approved'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('approved_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('pledge_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pledge_extensions');
    }
};
