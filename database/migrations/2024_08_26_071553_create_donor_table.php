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
        Schema::create('donors', function (Blueprint $table) {
            $table->unsignedBigInteger('donor_id')->autoIncrement();
            $table->string('donor_external_id' , 36)->comment('uuid')->unique()->index();
            $table->string('name' , 255);
            $table->string('email' , 255)->unique();
            $table->string('phone' , 15);
            $table->string('country_code',2);
            $table->string('postal_code' , 10);
            $table->string('address' , 255);
            $table->unsignedTinyInteger('is_public')->default(0);
            $table->string('display_name' , 255)->nullable();
            $table->string('corporate_no',20)->nullable();
            $table->text('message')->nullable();
            $table->charset('utf8mb4');
            $table->collation('utf8mb4_general_ci');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donors');
    }
};
