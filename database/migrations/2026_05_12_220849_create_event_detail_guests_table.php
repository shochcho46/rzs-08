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
        Schema::create('event_detail_guests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_detail_id')->constrained('event_details')->onDelete('cascade');
            $table->string('name');
            $table->string('mobile');
            $table->string('jersey_name');
            $table->string('jersey_number');
            $table->string('size')->comment('SM, M, L, XL, XXL or custom');
            $table->string('custom_width')->nullable();
            $table->string('custom_height')->nullable();
            $table->enum('sleeve_type', ['half_sleeve', 'full_sleeve']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_detail_guests');
    }
};
