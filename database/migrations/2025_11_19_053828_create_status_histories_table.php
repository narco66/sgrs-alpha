<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historiques_statuts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_id')->constrained('reunions')->onDelete('cascade');
            $table->string('status');
            $table->foreignId('changed_by')->constrained('utilisateurs');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historiques_statuts');
    }
};
