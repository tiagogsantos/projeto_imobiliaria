<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('sale')->nullable();
            $table->boolean('rent')->nullable();
            $table->string('category');
            $table->string('type');
            $table->unsignedInteger('user');

            /** Preços e valores */
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->decimal('rent_price', 10, 2)->nullable();
            $table->decimal('tribute', 10, 2)->nullable();
            $table->decimal('condominium', 10, 2)->nullable();

            /** Descrição do Imovel */
            $table->text('description')->nullable();

            /** Caracteristicas **Se eu não informar a quantidade irei informar ao banco que é zero */
            $table->integer('bedrooms')->default('0');
            $table->integer('suites')->default('0');
            $table->integer('bathrooms')->default('0');
            $table->integer('rooms')->default('0');
            $table->integer('garage')->default('0');
            $table->integer('garage_covered')->default('0');
            $table->integer('area_total')->default('0');
            $table->integer('area_util')->default('0');

            /** Endereço */
            $table->string('zipcode')->nullable();
            $table->string('street')->nullable();
            $table->string('number')->nullable();
            $table->string('complement')->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();

            /** Estrutura de o que contem no AP */
            $table->boolean('air_conditioning')->nullable();
            $table->boolean('bar')->nullable();
            $table->boolean('library')->nullable();
            $table->boolean('barbecue_grill')->nullable();
            $table->boolean('american_kitchen')->nullable();
            $table->boolean('fitted_kitchen')->nullable();
            $table->boolean('pantry')->nullable();
            $table->boolean('edicule')->nullable();
            $table->boolean('office')->nullable();
            $table->boolean('bathtub')->nullable();
            $table->boolean('fireplace')->nullable();
            $table->boolean('lavatory')->nullable();
            $table->boolean('furnished')->nullable();
            $table->boolean('pool')->nullable();
            $table->boolean('steam_room')->nullable();
            $table->boolean('view_of_the_sea')->nullable();
            $table->boolean('status')->nullable();

            $table->timestamps();

            /** Criando relacionamento com a minha tabela user */           

            $table->foreign('user')->references('id')->on('users')->onDelete('CASCADE');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('properties');
    }
}
