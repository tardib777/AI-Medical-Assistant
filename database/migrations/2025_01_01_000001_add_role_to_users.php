<?php
// ═══════════════════════════════════════════════
// migrations/2025_01_01_000001_add_role_to_users.php
// ═══════════════════════════════════════════════
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Add role + is_blocked to existing users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('user')->after('phone_number');
            $table->boolean('is_blocked')->default(false)->after('role');
        });
    }
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'is_blocked']);
        });
    }
};
