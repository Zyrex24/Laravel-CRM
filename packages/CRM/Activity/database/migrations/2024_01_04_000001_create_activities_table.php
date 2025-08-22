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
        Schema::create('crm_activities', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('type'); // call, meeting, email, task, note, lunch, demo, proposal
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'cancelled', 'no_show'])->default('scheduled');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->string('outcome')->nullable();
            
            // Scheduling
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->integer('duration_minutes')->nullable();
            
            // Location and meeting details
            $table->string('location')->nullable();
            $table->string('meeting_url')->nullable();
            
            // Reminders
            $table->timestamp('reminder_at')->nullable();
            $table->boolean('reminder_sent')->default(false);
            
            // Special flags
            $table->boolean('is_all_day')->default(false);
            $table->boolean('is_recurring')->default(false);
            $table->json('recurring_pattern')->nullable();
            $table->timestamp('recurring_until')->nullable();
            $table->foreignId('parent_activity_id')->nullable()->constrained('crm_activities')->onDelete('cascade');
            
            // Polymorphic relationship to related entities
            $table->string('related_type')->nullable();
            $table->unsignedBigInteger('related_id')->nullable();
            
            // Ownership and tracking
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            
            // Additional information
            $table->text('notes')->nullable();
            $table->json('custom_attributes')->nullable();
            $table->json('tags')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['related_type', 'related_id']);
            $table->index(['assigned_to', 'scheduled_at']);
            $table->index(['type', 'status']);
            $table->index('scheduled_at');
            $table->index('priority');
            $table->index('status');
            $table->index(['reminder_at', 'reminder_sent']);
            $table->index('is_recurring');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crm_activities');
    }
};
