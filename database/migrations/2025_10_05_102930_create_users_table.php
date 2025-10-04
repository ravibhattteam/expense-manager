<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); 
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            // Safe relations
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('role',['admin','manager','employee'])->default('employee');
            $table->boolean('is_manager_approver')->default(1);
        });
    }

    public function down(): void {
        Schema::dropIfExists('users');
    }
};
