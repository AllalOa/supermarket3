<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    
    public function up()
{
    Schema::create('suspensions', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id');
        $table->string('reason');
        $table->date('start_date')->default(now()); // Current date of suspension
        $table->date('end_date')->nullable(); // Nullable end date
        $table->timestamps();
        $table->integer('old_role');
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
}

        
    

    public function down() {
        Schema::dropIfExists('suspensions');
    }
};

