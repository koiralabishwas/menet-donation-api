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
        Schema::create('donations', function (Blueprint $table) {
            $table->unsignedBigInteger('donation_id')->autoIncrement()->unique()->index();
            $table->string('donation_external_id',20)->comment("20YYMMDD-ABC123");
            $table->unsignedBigInteger('donor_id');
            $table->string('donor_external_id',36)->comment('uuid')->index();
            $table->string('subscription_external_id',36)->comment('uuid')->nullable();
            $table->string('stripe_subscription_id',100)->nullable();
            $table->string('donation_project' , 50);
            $table->unsignedInteger('amount');
            $table->string('currency',3);
            $table->enum('type',['ONE_TIME','MONTHLY' , 'YEARLY']);
            $table->string('tax_deduction_certificate_url',1023);
            $table->foreign('donor_id')->references('donor_id')->on('donors');
            $table->foreign('donor_external_id')->references('donor_external_id')->on('donors');
            $table->foreign('subscription_external_id')->references('subscription_external_id')->on('subscriptions');
            $table->foreign('stripe_subscription_id')->references('stripe_subscription_id')->on('subscriptions');
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
        Schema::dropIfExists('donations');
    }
};
