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
        Schema::create('crm_role_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('crm_roles')->onDelete('cascade');
            $table->foreignId('permission_id')->constrained('crm_permissions')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['role_id', 'permission_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crm_role_permissions');
    }
};
