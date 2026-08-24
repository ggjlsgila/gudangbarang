<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->boolean('notifications_enabled')->default(true);
        $table->string('theme_mode')->default('light'); // Bisa untuk ekspansi fitur dark mode
        $table->string('company_name')->nullable(); // Khusus sistem ERP
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
