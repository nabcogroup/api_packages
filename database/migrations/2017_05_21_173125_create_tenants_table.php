<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTenantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('tenants', function (Blueprint $table) {
            
            $table->increments('id');

            $table->string('type',10)->index();
            
            $table->string('full_name',150);

            $table->string('email_address',50);

            $table->string('tel_no',50)->nullable();

            $table->string('mobile_no',50)->nullable();

            $table->string('fax_no',50)->nullable();

            $table->date('reg_date');

            $table->string('reg_id',150)->unique();

            $table->string('reg_name',150)->nullable();

            $table->string('gender',10)->nullable();

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
        Schema::dropIfExists('tenants');
    }
}
