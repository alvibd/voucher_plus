<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->string('campaign_name');
            $table->text('campaign_description')->nullable();
            $table->text('terms_and_conditions')->nullable();
            $table->date('launching_date');
            $table->date('expiration_date');
            $table->date('final_redemption_date');
            $table->unsignedBigInteger('vendor_id');
            $table->softDeletes('deleted_at', 0);
            $table->timestamps();

            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deals');
    }
}
