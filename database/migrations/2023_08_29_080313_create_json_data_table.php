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
        Schema::create('json_data', function (Blueprint $table) {
            $table->id();
            $table->json('data');
            $table->unsignedBigInteger('user_id');

            // Define the foreign key constraint
            $table->foreign('user_id')->references('id')->on('user')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('json_data');
        // $table->dropForeign(['user_id']);
        // $table->dropColumn('user_id');
    }
};
