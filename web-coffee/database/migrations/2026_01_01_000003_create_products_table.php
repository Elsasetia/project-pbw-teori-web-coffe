<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            // Kategori dihapus -> produk tetap ada (category_id jadi NULL)
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name', 100)->unique();
            $table->string('slug', 120)->unique();
            $table->text('description')->nullable();
            $table->unsignedInteger('price');          // dalam Rupiah, tanpa desimal
            $table->unsignedInteger('stock')->default(0);
            $table->string('image')->nullable();       // path relatif di disk "public"
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
