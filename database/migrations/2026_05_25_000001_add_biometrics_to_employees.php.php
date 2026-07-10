<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('biometric_id',  50)->nullable()->after('biometric_pin');
            $table->text('webauthn_credential_id')->nullable()->after('biometric_id');
            $table->text('webauthn_public_key')->nullable()->after('webauthn_credential_id');
            $table->boolean('biometric_enrolled')->default(false)->after('webauthn_public_key');
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'biometric_id',
                'webauthn_credential_id',
                'webauthn_public_key',
                'biometric_enrolled',
            ]);
        });
    }
};
