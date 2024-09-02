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
        Schema::create('subscription', function (Blueprint $table) {
            $table->unsignedBigInteger('subscription_id')->autoIncrement();
            $table->string('subscription_external_id',36)->unique()->comment('uuid')->nullable();
            $table->string('stripe_subscription_id',100)->comment('Stripe Subscription Id')->unique()->nullable();
            $table->unsignedBigInteger('donor_id');
            $table->string('donor_external_id' , 36)->comment('uuid')->index();
            $table->string('donation_project' , 50);
            $table->unsignedInteger('amount');
            $table->string('currency' , 3);
            $table->unsignedTinyInteger('is_cancelled')->default(0);
            $table->foreign('donor_id')->references('donor_id')->on('donors');
            $table->foreign('donor_external_id')->references('donor_external_id')->on('donors');
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
        Schema::dropIfExists('subscription');
    }
};
