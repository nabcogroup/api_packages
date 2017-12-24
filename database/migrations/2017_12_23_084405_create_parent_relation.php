<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParentRelation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contracts', function(Blueprint $table) {

            $table->foreign("villa_id")

                ->references("id")

                ->on("villas");

            $table->foreign("tenant_id")

                ->references("id")

                ->on("tenants");
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contracts', function(Blueprint $table) {

            $table->dropForeign("contracts_villa_id_foreign");

            $table->dropForeign("contracts_tenant_id_foreign");

        });

    }
}
