<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get the first category (Coffee) as default
        $defaultCategory = DB::table('categories')->first();
        
        if ($defaultCategory) {
            // Update all existing menus to have the default category
            DB::table('menus')->update(['category_id' => $defaultCategory->id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reset category_id to null
        DB::table('menus')->update(['category_id' => null]);
    }
};
