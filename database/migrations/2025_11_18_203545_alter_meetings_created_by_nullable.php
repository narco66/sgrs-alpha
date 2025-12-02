<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reunions', function (Blueprint $table) {
            // Si created_by est un entier simple
            // $table->unsignedBigInteger('created_by')->nullable()->change();

            // Si c’est une foreignId vers users :
            $table->foreignId('created_by')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('reunions', function (Blueprint $table) {
            // Revenir au NOT NULL si besoin (optionnel)
            // $table->foreignId('created_by')->nullable(false)->change();
        });
    }
};
