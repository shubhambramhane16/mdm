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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('Country name')->nullable();
            $table->string('code', 3)->unique()->comment('Country code, e.g., "IN" for India')->nullable();
            $table->boolean('status')->default(1)->comment('Status of the country, 1 for active, 0 for inactive');
            $table->string('created_by')->nullable()->comment('User who created the country');
            $table->string('updated_by')->nullable()->comment('User who last updated the country');
            $table->string('deleted_by')->nullable()->comment('User who deleted the country, if applicable');
            $table->softDeletes()->comment('Soft delete column for the country');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
