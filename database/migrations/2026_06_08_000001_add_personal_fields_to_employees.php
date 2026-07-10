<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('suffix', 10)->nullable()->after('middle_name');
            $table->date('date_of_birth')->nullable()->after('suffix');
            $table->enum('gender', ['male', 'female'])->nullable()->after('date_of_birth');
            $table->enum('civil_status', ['single', 'married', 'widowed', 'separated'])->nullable()->after('gender');
            $table->string('address', 255)->nullable()->after('phone');
            $table->string('emergency_contact_name', 100)->nullable()->after('address');
            $table->string('emergency_contact_number', 20)->nullable()->after('emergency_contact_name');
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'suffix', 'date_of_birth', 'gender', 'civil_status',
                'address', 'emergency_contact_name', 'emergency_contact_number',
            ]);
        });
    }
};
