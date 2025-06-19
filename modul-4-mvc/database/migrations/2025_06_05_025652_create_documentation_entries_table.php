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
        Schema::create('documentation_entries', function (Blueprint $table) {
            $table->id();
            $table->string('class_name');
            $table->text('source_path');
            $table->json('metadata')->nullable();
            $table->longText('documentation');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentation_entries');
    }
};
