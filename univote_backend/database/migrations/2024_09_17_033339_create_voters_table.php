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
        Schema::create('voters', function (Blueprint $table) {
            $table->id();
            $table->string('nic');
            $table->string('name');
            $table->string('password');
            $table->string('email');
            $table->string('reg_no');
            $table->string('faculty')->nullable();
            $table->string('level')->nullable();
            $table->boolean('emailVerified')->default(false);
            $table->boolean('isFirstTime')->default(true);
            $table->string('otp');
            // $table->string('academics_ref')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voters');
    }
};
