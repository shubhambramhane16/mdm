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
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('City name')->nullable();
            $table->unsignedBigInteger('state_id')->comment('Foreign key to states table');
            $table->boolean('status')->default(1)->comment('Status of the city, 1 for active, 0 for inactive');
            $table->string('created_by')->nullable()->comment('User who created the city');
            $table->string('updated_by')->nullable()->comment('User who last updated the city');
            $table->string('deleted_by')->nullable()->comment('User who deleted the city, if applicable');
            $table->softDeletes()->comment('Soft delete column for the city');
            $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade')->comment('Foreign key constraint to states table');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
