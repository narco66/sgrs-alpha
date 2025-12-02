<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('validations_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')
                ->constrained('documents')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->enum('validation_level', ['protocole', 'sg', 'president'])
                ->default('protocole');
            $table->enum('status', ['pending', 'approved', 'rejected'])
                ->default('pending');
            $table->text('comments')->nullable();
            $table->foreignId('validated_by')
                ->nullable()
                ->constrained('utilisateurs')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->timestamp('validated_at')->nullable();
            $table->timestamps();
            
            $table->index(['document_id', 'validation_level']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('validations_documents');
    }
};

