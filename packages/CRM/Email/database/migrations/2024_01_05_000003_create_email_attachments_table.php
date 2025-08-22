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
        
        Schema::create($tablePrefix . 'email_attachments', function (Blueprint $table) {
            $table->id();
            
            // Email reference
            $table->foreignId('email_id')->constrained($tablePrefix . 'emails')->onDelete('cascade');
            
            // File information
            $table->string('filename');
            $table->string('original_filename');
            $table->string('mime_type');
            $table->bigInteger('size'); // in bytes
            $table->string('path');
            $table->string('disk')->default('local');
            
            // Inline attachment support
            $table->boolean('is_inline')->default(false);
            $table->string('content_id')->nullable();
            
            // Additional metadata
            $table->json('metadata')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['email_id']);
            $table->index(['is_inline']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tablePrefix = config('crm.email.database.table_prefix', 'crm_');
        Schema::dropIfExists($tablePrefix . 'email_attachments');
    }
};
