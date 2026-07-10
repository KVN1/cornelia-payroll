<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_no', 20)->unique();
            $table->string('first_name', 80);
            $table->string('last_name', 80);
            $table->string('middle_name', 80)->nullable();
            $table->string('email', 150)->nullable()->unique();
            $table->string('phone', 20)->nullable();
            $table->foreignId('position_id')->constrained()->restrictOnDelete();
            $table->enum('employment_type', ['full_time','part_time','contractual'])->default('full_time');
            $table->date('hire_date');
            $table->date('end_date')->nullable();
            $table->enum('status', ['active','inactive','terminated'])->default('active');
            $table->string('sss_no', 20)->nullable();
            $table->string('philhealth_no', 20)->nullable();
            $table->string('pagibig_no', 20)->nullable();
            $table->string('tin_no', 20)->nullable();
            $table->decimal('daily_rate', 10, 2);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('employees'); }
};
