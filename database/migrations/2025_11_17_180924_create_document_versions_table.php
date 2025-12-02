<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('versions_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')
                ->constrained('documents')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->integer('version_number');
            $table->string('file_path');
            $table->string('file_name');
            $table->string('original_name');
            $table->unsignedBigInteger('file_size')->default(0);
            $table->string('mime_type')->nullable();
            $table->string('extension', 10)->nullable();
            $table->text('change_summary')->nullable();
            $table->foreignId('created_by')
                ->constrained('utilisateurs')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->timestamps();
            
            $table->unique(['document_id', 'version_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('versions_documents');
    }
};

