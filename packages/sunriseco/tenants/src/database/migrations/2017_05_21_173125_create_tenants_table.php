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
            
            $table->timestamps();

            $table->string('type',10)->index();

            $table->string('code',50)->unique();
            
            $table->string('full_name',150);

            $table->string('email_address',50);

            $table->string('tel_no',50);

            $table->string('mobile_no',50);

            $table->string('fax_no',50);

            $table->date('reg_date');

            $table->string('reg_id',150)->index();

            $table->string('reg_name',150);

            $table->string('gender',10)->nullable();

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
