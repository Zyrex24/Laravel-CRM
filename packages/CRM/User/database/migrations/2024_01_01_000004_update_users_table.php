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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('avatar')->nullable()->after('phone');
            $table->string('timezone')->default('UTC')->after('avatar');
            $table->string('locale')->default('en')->after('timezone');
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active')->after('locale');
            $table->timestamp('last_login_at')->nullable()->after('status');
            $table->boolean('two_factor_enabled')->default(false)->after('last_login_at');
            $table->text('two_factor_secret')->nullable()->after('two_factor_enabled');
            $table->text('two_factor_recovery_codes')->nullable()->after('two_factor_secret');
            $table->foreignId('team_id')->nullable()->constrained('crm_teams')->onDelete('set null')->after('two_factor_recovery_codes');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropForeign(['team_id']);
            $table->dropColumn([
                'phone',
                'avatar',
                'timezone',
                'locale',
                'status',
                'last_login_at',
                'two_factor_enabled',
                'two_factor_secret',
                'two_factor_recovery_codes',
                'team_id',
            ]);
        });
    }
};
