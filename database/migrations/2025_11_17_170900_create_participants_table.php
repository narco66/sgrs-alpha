<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->id();

            $table->string('last_name');
            $table->string('first_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();

            $table->string('position')->nullable();       // Fonction
            $table->string('institution')->nullable();    // Ministère, Direction, etc.
            $table->string('country')->nullable();        // Pays / État membre

            $table->boolean('is_internal')->default(true); // Agent CEEAC ou externe
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
