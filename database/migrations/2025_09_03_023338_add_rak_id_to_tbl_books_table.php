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
        $table->foreignId('rak_id')->nullable()->constrained('raks')->onDelete('set null');
    });
}

public function down()
{
    Schema::table('tbl_books', function (Blueprint $table) {
        $table->dropForeign(['rak_id']);
        $table->dropColumn('rak_id');
    });
}

};
