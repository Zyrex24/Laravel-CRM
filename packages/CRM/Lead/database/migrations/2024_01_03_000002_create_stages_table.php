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
        Schema::create('crm_stages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('probability')->default(0);
            $table->string('color', 7)->default('#6B7280');
            $table->boolean('is_closed')->default(false);
            $table->boolean('is_won')->default(false);
            $table->integer('sort_order')->default(0);
            $table->foreignId('pipeline_id')->constrained('crm_pipelines')->onDelete('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['pipeline_id', 'sort_order']);
            $table->index(['is_closed', 'is_won']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crm_stages');
    }
};
