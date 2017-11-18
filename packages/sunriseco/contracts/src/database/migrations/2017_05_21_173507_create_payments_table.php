<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {

            $table->increments('id');
            
            $table->integer('bill_id')->unsigned();

            $table->date('due_date');

            $table->string('payment_mode',50);

            $table->string('payment_type',50);

            $table->string('payment_no',50);

            $table->date('period_start');

            $table->date('period_end');

            $table->string('description',150)->nullable();

            $table->string('bank',50);

            $table->decimal('amount')->default(0.00);

            $table->string('remarks');

            $table->string('bank_account');

            $table->date('date_deposited')->nullable();

            $table->string('reference_no');

            $table->date('posted_date');

            $table->decimal('paid_amount');

            $table->string('status',20)->index();

            $table->integer('user_id')->index();

            $table->integer('parent_id')->index();

            $table->text('history')->nullable();

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
        Schema::dropIfExists('payments');
    }
}
