<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('category',['Online','Event','Tour']);
            $table->string('name');
            $table->string('location')->nullable();
            $table->string('schedule')->nullable();
            $table->string('price_includes')->nullable();
            $table->string('price_excludes')->nullable();
            $table->integer('price')->default(0)->nullable();
            $table->longText("description")->nullable();
            $table->enum('status',['open','close']);
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
        Schema::dropIfExists('products');
    }
}
