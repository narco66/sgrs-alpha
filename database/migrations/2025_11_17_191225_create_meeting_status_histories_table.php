<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('historiques_statuts_reunions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('meeting_id')
                ->constrained('reunions')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('old_status')->nullable();
            $table->string('new_status');
            $table->foreignId('changed_by')
                ->nullable()
                ->constrained('utilisateurs')
                ->nullOnDelete();

            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historiques_statuts_reunions');
    }
};
