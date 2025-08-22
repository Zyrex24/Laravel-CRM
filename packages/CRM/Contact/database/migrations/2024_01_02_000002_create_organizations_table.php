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
        Schema::create('crm_organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('legal_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('website')->nullable();
            $table->string('logo')->nullable();
            $table->string('industry')->nullable();
            $table->enum('size_category', ['startup', 'small', 'medium', 'large', 'enterprise'])->nullable();
            $table->decimal('annual_revenue', 15, 2)->nullable();
            $table->integer('number_of_employees')->nullable();
            $table->year('founded_year')->nullable();
            $table->text('description')->nullable();
            
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
            
            // Relationship flags
            $table->boolean('is_customer')->default(false);
            $table->boolean('is_vendor')->default(false);
            $table->boolean('is_partner')->default(false);
            
            // Hierarchy
            $table->foreignId('parent_organization_id')->nullable()->constrained('crm_organizations')->onDelete('set null');
            
            // Ownership and tracking
            $table->foreignId('owner_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('name');
            $table->index('email');
            $table->index('industry');
            $table->index('size_category');
            $table->index('owner_id');
            $table->index('lead_status');
            $table->index(['is_customer', 'is_vendor', 'is_partner']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crm_organizations');
    }
};
