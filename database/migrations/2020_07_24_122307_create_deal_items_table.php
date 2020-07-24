<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deal_items', function (Blueprint $table) {
            $table->id();
            $table->string('item_name');
            $table->text('item_description');
            $table->integer('original_price');
            $table->integer('offered_price');
            $table->integer('quantity');
            $table->unsignedBigInteger('deal_id');
            $table->unsignedBigInteger('vendor_id');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('deal_id')->references('id')->on('deals')->onDelete('cascade');
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
        Schema::dropIfExists('deal_items');
    }
}
