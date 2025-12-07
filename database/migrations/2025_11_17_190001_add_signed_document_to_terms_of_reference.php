<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Ajout de la possibilité de joindre le document physique signé
     */
    public function up(): void
    {
        Schema::table('cahiers_charges', function (Blueprint $table) {
            // Document physique signé (PDF ou image scannée)
            $table->string('signed_document_path')->nullable()->after('pdf_path');
            $table->string('signed_document_name')->nullable()->after('signed_document_path');
            $table->string('signed_document_original_name')->nullable()->after('signed_document_name');
            $table->unsignedBigInteger('signed_document_size')->nullable()->after('signed_document_original_name');
            $table->string('signed_document_mime_type')->nullable()->after('signed_document_size');
            $table->string('signed_document_extension', 10)->nullable()->after('signed_document_mime_type');
            $table->timestamp('signed_document_uploaded_at')->nullable()->after('signed_document_extension');
            $table->foreignId('signed_document_uploaded_by')
                ->nullable()
                ->after('signed_document_uploaded_at')
                ->constrained('utilisateurs')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cahiers_charges', function (Blueprint $table) {
            $table->dropForeign(['signed_document_uploaded_by']);
            $table->dropColumn([
                'signed_document_path',
                'signed_document_name',
                'signed_document_original_name',
                'signed_document_size',
                'signed_document_mime_type',
                'signed_document_extension',
                'signed_document_uploaded_at',
                'signed_document_uploaded_by',
            ]);
        });
    }
};





