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
        Schema::create('states', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('State name')->nullable();
            $table->string('code', 3)->unique()->comment('State code, e.g., "MH" for Maharashtra')->nullable();
            $table->unsignedBigInteger('country_id')->comment('Foreign key to countries table');
            $table->boolean('status')->default(1)->comment('Status of the state, 1 for active, 0 for inactive');
            $table->string('created_by')->nullable()->comment('User who created the state');
            $table->string('updated_by')->nullable()->comment('User who last updated the state');
            $table->string('deleted_by')->nullable()->comment('User who deleted the state, if applicable');
            $table->softDeletes()->comment('Soft delete column for the state');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade')->comment('Foreign key constraint to countries table');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('states');
    }
};
