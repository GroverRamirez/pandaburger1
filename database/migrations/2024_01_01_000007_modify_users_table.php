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
            // Check and drop existing columns if they exist
            if (Schema::hasColumn('users', 'name')) {
                $table->dropColumn('name');
            }
            if (Schema::hasColumn('users', 'email')) {
                $table->dropColumn('email');
            }
            if (Schema::hasColumn('users', 'email_verified_at')) {
                $table->dropColumn('email_verified_at');
            }
            if (Schema::hasColumn('users', 'password')) {
                $table->dropColumn('password');
            }
            if (Schema::hasColumn('users', 'remember_token')) {
                $table->dropColumn('remember_token');
            }
            
            // Add new columns according to schema
            if (!Schema::hasColumn('users', 'rol_id')) {
                $table->foreignId('rol_id')->constrained('roles');
            }
            if (!Schema::hasColumn('users', 'nombre')) {
                $table->string('nombre', 60);
            }
            if (!Schema::hasColumn('users', 'correo_electronico')) {
                $table->string('correo_electronico', 120)->unique();
            }
            if (!Schema::hasColumn('users', 'telefono')) {
                $table->string('telefono', 30)->nullable();
            }
            if (!Schema::hasColumn('users', 'password_hash')) {
                $table->string('password_hash');
            }
            if (!Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable();
            }
            if (!Schema::hasColumn('users', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop new columns
            if (Schema::hasColumn('users', 'rol_id')) {
                $table->dropForeign(['rol_id']);
                $table->dropColumn('rol_id');
            }
            if (Schema::hasColumn('users', 'nombre')) {
                $table->dropColumn('nombre');
            }
            if (Schema::hasColumn('users', 'correo_electronico')) {
                $table->dropColumn('correo_electronico');
            }
            if (Schema::hasColumn('users', 'telefono')) {
                $table->dropColumn('telefono');
            }
            if (Schema::hasColumn('users', 'password_hash')) {
                $table->dropColumn('password_hash');
            }
            if (Schema::hasColumn('users', 'last_login_at')) {
                $table->dropColumn('last_login_at');
            }
            if (Schema::hasColumn('users', 'deleted_at')) {
                $table->dropColumn('deleted_at');
            }
            
            // Restore original columns
            if (!Schema::hasColumn('users', 'name')) {
                $table->string('name');
            }
            if (!Schema::hasColumn('users', 'email')) {
                $table->string('email')->unique();
            }
            if (!Schema::hasColumn('users', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable();
            }
            if (!Schema::hasColumn('users', 'password')) {
                $table->string('password');
            }
            if (!Schema::hasColumn('users', 'remember_token')) {
                $table->rememberToken();
            }
        });
    }
}; 