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
        Schema::create('crm_leads', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            
            // Polymorphic contact relationship
            $table->string('contact_type')->nullable();
            $table->unsignedBigInteger('contact_id')->nullable();
            
            // Financial information
            $table->decimal('value', 15, 2)->default(0);
            $table->string('currency', 3)->default('USD');
            $table->integer('probability')->default(0);
            
            // Dates
            $table->date('expected_close_date')->nullable();
            $table->date('actual_close_date')->nullable();
            
            // Lead classification
            $table->string('source')->nullable();
            $table->string('type')->nullable();
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            
            // Scoring and activity tracking
            $table->integer('score')->default(0);
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamp('next_follow_up_at')->nullable();
            
            // Rotten lead detection
            $table->boolean('is_rotten')->default(false);
            $table->integer('rotten_days')->default(0);
            
            // Additional information
            $table->json('tags')->nullable();
            $table->json('custom_attributes')->nullable();
            $table->text('notes')->nullable();
            
            // Pipeline and stage
            $table->foreignId('pipeline_id')->constrained('crm_pipelines')->onDelete('cascade');
            $table->foreignId('stage_id')->constrained('crm_stages')->onDelete('cascade');
            
            // Ownership and tracking
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['contact_type', 'contact_id']);
            $table->index(['pipeline_id', 'stage_id']);
            $table->index('assigned_to');
            $table->index('expected_close_date');
            $table->index('priority');
            $table->index('score');
            $table->index('is_rotten');
            $table->index('source');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crm_leads');
    }
};
