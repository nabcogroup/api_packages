<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_bills', function (Blueprint $table) {

            $table->increments('id');

            $table->string('bill_no', 50)->unique();

            $table->integer('contract_id')->index()->unsigned();

            $table->integer('user_id')->index();

            $table->boolean('closed')->index();

            $table->timestamps();

            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contract_bills');
    }
}
