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
        Schema::create('crm_persons', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name')->nullable();
            $table->string('title')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('fax')->nullable();
            $table->string('website')->nullable();
            $table->string('avatar')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->nullable();
            $table->string('job_title')->nullable();
            $table->string('department')->nullable();
            
            // Address fields
            $table->string('address_line_1')->nullable();
            $table->string('address_line_2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable();
            
            // Preferences
            $table->string('timezone')->default('UTC');
            $table->string('locale')->default('en');
            
            // Additional information
            $table->text('notes')->nullable();
            $table->json('tags')->nullable();
            $table->json('social_profiles')->nullable();
            $table->json('custom_attributes')->nullable();
            
            // Lead information
            $table->string('lead_source')->nullable();
            $table->string('lead_status')->nullable();
            
            // Flags
            $table->boolean('is_vip')->default(false);
            $table->boolean('do_not_call')->default(false);
            $table->boolean('do_not_email')->default(false);
            $table->boolean('email_opt_out')->default(false);
            
            // Ownership and tracking
            $table->foreignId('owner_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['first_name', 'last_name']);
            $table->index('email');
            $table->index('phone');
            $table->index('owner_id');
            $table->index('lead_status');
            $table->index('is_vip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crm_persons');
    }
};
