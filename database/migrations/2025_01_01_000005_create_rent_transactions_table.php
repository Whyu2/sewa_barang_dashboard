<?php
// database/migrations/2025_01_01_000003_create_rent_transactions_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rent_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('region_id')->constrained('regions')->onDelete('cascade');
            $table->string('renter_name');
            $table->string('renter_phone')->nullable();
            $table->dateTime('rent_date');
            $table->integer('rent_price');
            $table->integer('qty');
            $table->dateTime('expected_return_date');
            $table->dateTime('return_date')->nullable();
            $table->string('status')->default('rented');
            // rented, pending, returned, overdue
            $table->text('notes')->nullable();
            $table->string('pickup_proof_url')->nullable();
            $table->string('return_proof_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rent_transactions');
    }
};
