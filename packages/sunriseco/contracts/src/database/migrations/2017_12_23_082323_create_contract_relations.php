<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractRelations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('contract_terminations',function(Blueprint $table) {

            $table->foreign('contract_id')
                ->references('id')
                ->on('contracts')
                ->onDelete('cascade');
        });

        Schema::table('contract_bills',function(Blueprint $table) {

            $table->foreign('contract_id')
                ->references('id')
                ->on('contracts')
                ->onDelete('cascade');
        });

        Schema::table('payments',function(Blueprint $table) {
           $table->foreign('bill_id')
               ->references('id')
               ->on('contracts')
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

        Schema::table('contract_terminations',function(Blueprint $table) {
            $table->dropForeign('contract_terminations_contract_id_foreign');

        });

        Schema::table('contract_bills',function(Blueprint $table) {
            $table->dropForeign('contract_bills_contract_id_foreign');

        });

        Schema::table('payments',function(Blueprint $table) {
            $table->dropForeign('payments_bill_id_foreign');
        });
    }
}
