<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('iad__bids', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            // Your fields...

            $table->integer('ad_id')->unsigned();
            $table->foreign('ad_id')->references('id')->on('iad__ads')->onDelete('restrict');

            $table->double('amount', 30, 2)->default(0);
            $table->text('description');
            $table->string('currency')->default('USD');
            $table->integer('delivery_days')->default(0)->unsigned();
            $table->integer('selected')->default(0)->unsigned();
            $table->integer('status_id')->default(0)->unsigned();

            $table->text('options')->nullable();

            // Audit fields
            $table->timestamps();
            $table->auditStamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('iad__bids');
    }
};
