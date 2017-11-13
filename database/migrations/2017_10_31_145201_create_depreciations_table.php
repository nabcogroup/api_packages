<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepreciationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('depreciations', function (Blueprint $table) {
            
            $table->increments('id');

            $table->integer('fixed_asset_id')->unsigned();

            $table->date('ob_date');

            $table->decimal('ob_amount');

            $table->integer('ob_year');

            $table->decimal('depreciated_value');

            $table->decimal('book_value');

            $table->string('acct_code');

            $table->timestamps();

             //foreign key
             $table->foreign('fixed_asset_id')
             ->references('id')
             ->on('fixed_assets')
             ->onDelete('cascade');
        });
    }
   
    public function down()
    {
        Schema::dropIfExists('depreciations');
    }
}
