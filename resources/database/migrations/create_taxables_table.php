<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaxablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'taxables', function ( Blueprint $table ) {
            $table->integer('taxonomy_id')
                ->nullable()
                ->unsigned()
                ->references('id')
                ->on('taxonomies');

            $table->nullableMorphs('taxable');
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists( 'taxables' );
    }
}
