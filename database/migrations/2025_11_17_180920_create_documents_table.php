<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->text('description')->nullable();

            $table->string('file_path');   // chemin dans storage/app ou public
            $table->string('file_name');   // nom stocké (unique)
            $table->string('original_name'); // nom original uploadé
            $table->unsignedBigInteger('file_size')->default(0); // en octets
            $table->string('mime_type')->nullable();
            $table->string('extension', 10)->nullable();

            $table->enum('document_type', [
                'ordre_du_jour',
                'rapport',
                'pv',
                'presentation',
                'note',
                'autre',
            ])->default('autre');

            $table->foreignId('meeting_id')
                ->nullable()
                ->constrained('reunions')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreignId('uploaded_by')
                ->constrained('utilisateurs')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->boolean('is_shared')->default(true);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
