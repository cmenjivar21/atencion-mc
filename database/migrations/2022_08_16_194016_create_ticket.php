<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket', function (Blueprint $table) {
            $table->id();
            $table->string('client_name');
            $table->string('client_last_name');
            $table->string('client_dui');
            $table->string('description');
            $table->string('solution');
            $table->string('phone');
            $table->foreignId('sub_categorie_id')->references('id')->on('sub_categorie')->comment('SubCategoria');
            $table->foreignId('user_id')->references('id')->on('users')->comment('Usuario');
            $table->foreignId('municipality_id')->references('id')->on('municipalities')->comment('Municipio');
            $table->boolean('finished')->default(false);
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
        Schema::dropIfExists('ticket');
    }
};
