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
        
        Schema::create($tablePrefix . 'emails', function (Blueprint $table) {
            $table->id();
            
            // Email content
            $table->string('subject');
            $table->longText('body')->nullable();
            $table->longText('body_html')->nullable();
            $table->longText('body_text')->nullable();
            
            // Sender information
            $table->string('from_email');
            $table->string('from_name')->nullable();
            
            // Recipient information
            $table->string('to_email');
            $table->string('to_name')->nullable();
            $table->json('cc_emails')->nullable();
            $table->json('bcc_emails')->nullable();
            
            // Reply information
            $table->string('reply_to_email')->nullable();
            $table->string('reply_to_name')->nullable();
            
            // Email headers and threading
            $table->string('message_id')->unique()->nullable();
            $table->string('thread_id')->nullable();
            $table->string('in_reply_to')->nullable();
            $table->text('references')->nullable();
            
            // Email metadata
            $table->enum('direction', ['inbound', 'outbound'])->default('outbound');
            $table->enum('status', ['draft', 'scheduled', 'queued', 'sending', 'sent', 'delivered', 'opened', 'clicked', 'bounced', 'failed', 'spam'])->default('draft');
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            $table->enum('type', ['regular', 'template', 'campaign', 'automated', 'transactional'])->default('regular');
            
            // Template and campaign references
            $table->foreignId('template_id')->nullable()->constrained($tablePrefix . 'email_templates')->onDelete('set null');
            $table->string('campaign_id')->nullable();
            
            // Timestamps
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('first_opened_at')->nullable();
            $table->timestamp('clicked_at')->nullable();
            $table->timestamp('first_clicked_at')->nullable();
            $table->timestamp('bounced_at')->nullable();
            $table->timestamp('replied_at')->nullable();
            $table->timestamp('unsubscribed_at')->nullable();
            $table->timestamp('spam_reported_at')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            
            // Tracking metrics
            $table->integer('open_count')->default(0);
            $table->integer('click_count')->default(0);
            $table->string('bounce_reason')->nullable();
            $table->text('error_message')->nullable();
            $table->boolean('tracking_enabled')->default(true);
            
            // Relationships
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('related_type')->nullable();
            $table->unsignedBigInteger('related_id')->nullable();
            
            // Additional data
            $table->json('metadata')->nullable();
            $table->json('headers')->nullable();
            $table->longText('raw_content')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['direction', 'status']);
            $table->index(['user_id', 'direction']);
            $table->index(['related_type', 'related_id']);
            $table->index(['thread_id']);
            $table->index(['status', 'scheduled_at']);
            $table->index(['sent_at']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tablePrefix = config('crm.email.database.table_prefix', 'crm_');
        Schema::dropIfExists($tablePrefix . 'emails');
    }
};
