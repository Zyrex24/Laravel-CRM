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
        
        Schema::create($tablePrefix . 'email_templates', function (Blueprint $table) {
            $table->id();
            
            // Template identification
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            
            // Template content
            $table->string('subject');
            $table->longText('body_html')->nullable();
            $table->longText('body_text')->nullable();
            
            // Template categorization
            $table->enum('category', ['welcome', 'follow_up', 'reminder', 'notification', 'marketing', 'support'])->default('notification');
            $table->enum('type', ['system', 'user', 'campaign', 'automated', 'transactional'])->default('user');
            $table->string('language', 5)->default('en');
            
            // Template configuration
            $table->json('variables')->nullable();
            $table->json('settings')->nullable();
            $table->json('metadata')->nullable();
            
            // Template status
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            
            // Versioning
            $table->foreignId('parent_id')->nullable()->constrained($tablePrefix . 'email_templates')->onDelete('cascade');
            $table->integer('version')->default(1);
            
            // Ownership
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['category', 'is_active']);
            $table->index(['type', 'is_active']);
            $table->index(['language', 'is_active']);
            $table->index(['user_id']);
            $table->index(['parent_id', 'version']);
            $table->index(['is_default', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tablePrefix = config('crm.email.database.table_prefix', 'crm_');
        Schema::dropIfExists($tablePrefix . 'email_templates');
    }
};
