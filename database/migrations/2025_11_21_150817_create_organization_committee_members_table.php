<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('membres_comites_organisation', function (Blueprint $table) {
                $table->id();
                $table->foreignId('organization_committee_id')
                    ->constrained('comites_organisation')
                    ->cascadeOnDelete();
                $table->foreignId('user_id')
                    ->constrained('utilisateurs')
                    ->cascadeOnUpdate()
                    ->restrictOnDelete();
                $table->string('role')->default('member'); // member, president, secretary, etc.
                $table->text('notes')->nullable();
                $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membres_comites_organisation');
    }
};
