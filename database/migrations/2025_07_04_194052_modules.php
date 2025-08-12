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
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('class_name')->nullable();
            $table->string('slug')->nullable();
            $table->string('icon')->nullable();
            // parent_id is used for hierarchical modules
            $table->unsignedBigInteger('parent_id')->comment('Parent module ID for hierarchical structure')->default(0);
            $table->string('description')->nullable();
            $table->string('is_published')->default('0'); // 0 for unpublished, 1 for published
            $table->boolean('is_left_menu')->default(false); // 0 for not left menu, 1 for left menu
            $table->boolean('is_accordion')->default(false); // 0 for not accordion, 1 for accordion
            $table->integer('sort_order')->default(1);
            $table->json('add_form')->nullable(); // JSON to store add form fields
            $table->json('edit_form')->nullable(); // JSON to store edit form fields
            $table->json('view_list')->nullable(); // JSON to store view list fields
            $table->boolean('status')->default(true); // 1 for active, 0 for inactive
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes(); // For 'deleted_at'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
