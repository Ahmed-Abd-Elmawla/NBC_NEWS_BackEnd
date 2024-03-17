<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert default user into the users table
        DB::table('users')->insert([
            'name' => 'Ahmed Abd ElMawla',
            'email' => 'ahmed@admin.com',
            'password' => bcrypt('Aa1111##'),
            'role_id' => '100',
            'image' => 'admin.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')->where('email', 'ahmed@admin.com')->delete();    }
};
