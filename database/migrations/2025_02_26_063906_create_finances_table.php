<?php

use App\Enums\FinanceType;
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
        Schema::create('finances', function (Blueprint $table) {
            $table->id();
            $table->enum('type',enum_values(FinanceType::class));
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('expense_id')->nullable()->constrained('expenses')->cascadeOnDelete();
            $table->decimal('amount',8,2);
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finances');
    }
};
