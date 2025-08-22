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
        $tablePrefix = config('crm.email.database.table_prefix', 'crm_');
        
        Schema::create($tablePrefix . 'email_tracking', function (Blueprint $table) {
            $table->id();
            
            // Email reference
            $table->foreignId('email_id')->constrained($tablePrefix . 'emails')->onDelete('cascade');
            
            // Event information
            $table->enum('event_type', ['sent', 'delivered', 'opened', 'clicked', 'bounced', 'spam', 'unsubscribed']);
            $table->timestamp('timestamp');
            
            // User agent and device information
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('location')->nullable();
            $table->string('device_type')->nullable();
            $table->string('browser')->nullable();
            $table->string('operating_system')->nullable();
            
            // Click tracking
            $table->text('url')->nullable();
            $table->text('clicked_url')->nullable();
            
            // Additional metadata
            $table->json('metadata')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['email_id', 'event_type']);
            $table->index(['event_type', 'timestamp']);
            $table->index(['timestamp']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tablePrefix = config('crm.email.database.table_prefix', 'crm_');
        Schema::dropIfExists($tablePrefix . 'email_tracking');
    }
};
