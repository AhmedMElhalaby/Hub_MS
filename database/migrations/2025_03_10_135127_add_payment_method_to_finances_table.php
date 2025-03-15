<?php

use App\Enums\PaymentMethod;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('finances', function (Blueprint $table) {
            $table->tinyInteger('payment_method')->default(PaymentMethod::Cash->value)->after('type');
        });
    }

    public function down(): void
    {
        Schema::table('finances', function (Blueprint $table) {
            $table->dropColumn('payment_method');
        });
    }
};
