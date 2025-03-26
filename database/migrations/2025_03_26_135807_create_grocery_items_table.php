<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('grocery_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grocery_list_id')->constrained()->onDelete('cascade'); // Links to grocery_lists table
            $table->string('title');
            $table->boolean('checked')->default(false);
            $table->integer('position');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grocery_items');
    }
};
