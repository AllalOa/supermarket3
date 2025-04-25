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
        Schema::create('foyers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Store name
            $table->unsignedBigInteger('chief_id'); // FK to users table
            $table->unsignedBigInteger('stat'); // Status: 0 or 1
            $table->text('description')->nullable(); // Any other info
            $table->timestamps();

            $table->foreign('chief_id')->references('id')->on('users')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foyers');
    }
};
