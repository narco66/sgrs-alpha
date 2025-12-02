<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('types_reunions', function (Blueprint $table) {
            $table->id();
            $table->string('name');                 // Conseil des Ministres, Conférence des Chefs d'État...
            $table->string('code')->unique();       // "CDM", "CCE", etc.
            $table->string('color', 20)->nullable(); // pour badges (ex: "primary", "#0d6efd")
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('requires_president_approval')->default(false);
            $table->boolean('requires_sg_approval')->default(true);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('types_reunions');
    }
};
