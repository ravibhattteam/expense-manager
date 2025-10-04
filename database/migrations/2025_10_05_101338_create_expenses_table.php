<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('amount',12,2);
            $table->string('currency',3);
            $table->decimal('amount_in_company_currency',12,2)->nullable();
            $table->string('category');
            $table->text('description')->nullable();
            $table->date('expense_date');
            $table->enum('status',['pending','approved','rejected'])->default('pending');
            $table->foreignId('current_approver_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('receipt_path')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('expenses');
    }
};
