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
        Schema::create('crm_person_organizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained('crm_persons')->onDelete('cascade');
            $table->foreignId('organization_id')->constrained('crm_organizations')->onDelete('cascade');
            $table->string('role')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();

            $table->unique(['person_id', 'organization_id']);
            $table->index(['person_id', 'is_primary']);
            $table->index(['organization_id', 'is_primary']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crm_person_organizations');
    }
};
