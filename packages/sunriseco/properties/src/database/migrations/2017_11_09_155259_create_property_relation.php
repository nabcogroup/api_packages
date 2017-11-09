<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertyRelation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('villas', function (Blueprint $table) {

            //foreign key
            $table->foreign('property_id')
            ->references('id')
            ->on('properties')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //products  - categories
        Schema::table('villas', function(Blueprint $table) {
            $table->dropForeign('villas_property_id_foreign');
        });
    }
}
