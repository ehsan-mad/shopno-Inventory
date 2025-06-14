<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Add user_id column as nullable first
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
        });

        // Handle existing products - assign them to a user
        $this->handleExistingProducts();

        Schema::table('products', function (Blueprint $table) {
            // Make it non-nullable and add foreign key constraint
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'status']); // For faster queries
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropIndex(['user_id', 'status']);
            $table->dropColumn('user_id');
        });
    }

    /**
     * Handle existing products by assigning them to a user
     */
    private function handleExistingProducts()
    {
        // Check if there are any existing products
        $productCount = DB::table('products')->count();
        
        if ($productCount > 0) {
            // Get the first user, or create one if none exists
            $user = User::where('id' , 3)->first();

            if (!$user) {
                // Create a default admin user if no users exist
                $user = User::create([
                    'name' => 'System Admin',
                    'email' => 'admin@system.com',
                    'password' => bcrypt('password123'),
                    'email_verified_at' => now(),
                ]);
            }

            // Update all existing products to belong to this user
            DB::table('products')
                ->whereNull('user_id')
                ->update(['user_id' => $user->id]);
        }
    }
};
