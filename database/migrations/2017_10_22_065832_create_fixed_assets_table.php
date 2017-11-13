<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFixedAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fixed_assets', function (Blueprint $table) {
            
            $table->increments('id');

            $table->date('purchase_date');

            $table->string('description');

            $table->string('fixed_asset_type',100);

            $table->string('property_code',50);

            $table->decimal('cost');

            $table->string('tag_code')->nullable();

            $table->string('serial_no')->nullable();

            $table->integer('year_span');

            $table->string('salvage_value');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fixed_assets');
    }
}
