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
            // Drop existing columns
            $table->dropColumn(['name', 'email', 'email_verified_at', 'password', 'remember_token']);
            
            // Add new columns according to schema
            $table->foreignId('rol_id')->constrained('roles');
            $table->string('nombre', 60);
            $table->string('correo_electronico', 120)->unique();
            $table->string('telefono', 30)->nullable();
            $table->string('password_hash');
            $table->timestamp('last_login_at')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['rol_id']);
            $table->dropColumn(['rol_id', 'nombre', 'correo_electronico', 'telefono', 'password_hash', 'last_login_at', 'deleted_at']);
            
            // Restore original columns
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
        });
    }
}; 