<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {

            $table->increments('id');

            $table->string('contract_no',50)->unique();
            
            $table->string('contract_type',20);
            
            $table->date('period_start')->nullable();
            
            $table->date('period_end')->nullable();

            $table->integer('period_end_extended')->default(0);
            
            $table->decimal('amount')->default(0);

            $table->integer('villa_id')->index()->unsigned();

            $table->integer('tenant_id')->index()->unsigned();

            $table->integer('user_id')->index();

            $table->string('status',10)->index();

            $table->text('meta_data')->nullable();

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
        Schema::dropIfExists('contracts');
    }
}
