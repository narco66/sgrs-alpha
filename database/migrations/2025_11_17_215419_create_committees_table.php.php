<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('comites', function (Blueprint $table) {
            $table->id();
            $table->string('name');            // Comité des Experts, Comité de suivi, etc.
            $table->string('code')->unique();  // "CE", "CS", etc.
            $table->foreignId('meeting_type_id')
                ->nullable()
                ->constrained('types_reunions')
                ->nullOnDelete();
            $table->boolean('is_permanent')->default(true);
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comites');
    }
};
