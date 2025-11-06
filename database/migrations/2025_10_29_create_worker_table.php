<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('workers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('phone');
            $table->boolean('has_whatsapp')->default(false);
            $table->string('dni');
            $table->string('cuil_cuit');
            $table->string('tax_status')->default('ninguna'); // monotributo / responsable inscripto / ninguna
            $table->json('skills')->nullable();
            $table->string('dni_front_path')->nullable();
            $table->string('dni_back_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workers');
    }
};
