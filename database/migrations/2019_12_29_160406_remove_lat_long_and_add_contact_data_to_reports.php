<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveLatLongAndAddContactDataToReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn(['lat', 'lon']);
            $table->string('email')->nullable()->after('details');
            $table->string('phone')->nullable()->after('details');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->decimal('lat', 9, 6);
            $table->decimal('lon', 9, 6);
            $table->dropColumn(['email', 'phone']);
        });
    }
}
