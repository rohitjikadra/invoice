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
        Schema::create('delivery_challans', function (Blueprint $table) {
            $table->id();
            $table->string('challan_number')->unique();
            $table->foreignId('customer_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->date('challan_date');
            $table->string('vehicle_no')->nullable();
            $table->string('eway_bill_no')->nullable();
            $table->string('quality')->nullable();
            $table->string('broker')->nullable();
            $table->string('receiver_name');
            $table->text('receiver_address');
            $table->string('receiver_gstin')->nullable();
            $table->string('consignee_name');
            $table->text('consignee_address');
            $table->string('consignee_gstin')->nullable();
            $table->text('remark')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['customer_id', 'challan_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_challans');
    }
};
