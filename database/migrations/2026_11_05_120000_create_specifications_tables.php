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
        Schema::create('spec_attributes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 120)->unique();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('spec_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spec_attribute_id')->constrained('spec_attributes')->cascadeOnDelete();
            $table->string('value', 255);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('product_specifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('spec_attribute_id')->constrained('spec_attributes')->cascadeOnDelete();
            $table->string('value', 255);
            $table->timestamps();

            $table->unique(['product_id', 'spec_attribute_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_specifications');
        Schema::dropIfExists('spec_attribute_values');
        Schema::dropIfExists('spec_attributes');
    }
};
