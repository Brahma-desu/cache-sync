<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if(!schema::hasTable('user_records')){
            Schema::create('user_records', function (Blueprint $table) {
                $table->id();
                $table->string('name', 255)->nullable();
                $table->string('email', 255)->nullable();
                $table->string('password', 10);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_records');
    }
};
