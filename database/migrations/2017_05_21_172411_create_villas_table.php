<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVillasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('villas', function (Blueprint $table) {
            
            $table->increments('id');

            $table->string("villa_no",20)->unique();

            $table->integer("property_id")->unsigned();

            $table->string("geo_location")->nullable();

            $table->string("electricity_no",150)->nullable();

            $table->string("water_no",150)->nullable();

            $table->string("qtel_no",150)->nullable();

            $table->string("villa_class",50)->index();

            $table->integer("capacity")->default(0);

            $table->longText('description')->nullable();

            $table->decimal("rate_per_month")->default(0);
            
            $table->string("status",15)->default('vacant');

            $table->date("last_date_occupied")->nullable();

            $table->text('image_galleries')->nullable();

             $table->softDeletes();

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
        Schema::dropIfExists('villas');
    }
}
