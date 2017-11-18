<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractTerminationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_terminations', function (Blueprint $table) {

            $table->integer('contract_id')->unsigned()->index();

            $table->text('description');

            $table->string('ref_no');

            $table->primary('contract_id');

            $table->decimal('total_balance_payment');

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
        Schema::dropIfExists('contract_terminations');
    }
}
