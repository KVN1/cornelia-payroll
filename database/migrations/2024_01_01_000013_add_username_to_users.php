<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 50)->unique()->after('name');
        });

        // Copy existing name to username as default (slugified)
        DB::table('users')->get()->each(function ($user) {
            $username = strtolower(str_replace(' ', '_', $user->name));
            DB::table('users')->where('id', $user->id)->update(['username' => $username]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['email']);
            $table->string('email', 150)->nullable()->change();
        });
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('username');
            $table->string('email', 150)->nullable(false)->change();
        });
    }
};
