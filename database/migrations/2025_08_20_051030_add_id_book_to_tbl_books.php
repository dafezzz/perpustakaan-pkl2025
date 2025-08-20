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
    Schema::table('tbl_books', function (Blueprint $table) {
        $table->id('id_book')->first();
    });
}

public function down()
{
    Schema::table('tbl_books', function (Blueprint $table) {
        $table->dropColumn('id_book');
    });
}

};
