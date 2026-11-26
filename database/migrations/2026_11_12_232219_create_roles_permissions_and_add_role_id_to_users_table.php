<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
        });

        Schema::create('permission_role', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
            $table->primary(['role_id', 'permission_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->after('role')->constrained()->nullOnDelete();
        });

        foreach (DB::table('users')->select(['id', 'role'])->get() as $user) {
            $legacyRole = trim((string) $user->role);
            $roleName = match (mb_strtolower($legacyRole)) {
                'admin', 'administrador' => 'Administrador',
                'cliente', 'client' => 'Cliente',
                default => $legacyRole !== '' ? $legacyRole : 'user',
            };

            DB::table('roles')->insertOrIgnore(['name' => $roleName]);

            $roleId = DB::table('roles')->where('name', $roleName)->value('id');
            DB::table('users')->where('id', $user->id)->update(['role_id' => $roleId]);
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('role_id');
        });

        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
};
