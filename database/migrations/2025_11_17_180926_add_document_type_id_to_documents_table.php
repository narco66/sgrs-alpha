<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->foreignId('document_type_id')
                ->nullable()
                ->after('document_type')
                ->constrained('types_documents')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            
            $table->enum('validation_status', ['draft', 'pending', 'approved', 'rejected', 'archived'])
                ->default('draft')
                ->after('is_shared');
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeign(['document_type_id']);
            $table->dropColumn(['document_type_id', 'validation_status']);
        });
    }
};

