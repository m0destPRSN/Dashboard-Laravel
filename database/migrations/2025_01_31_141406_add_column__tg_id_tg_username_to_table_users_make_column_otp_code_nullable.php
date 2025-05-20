<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnTgIdTgUsernameToTableUsersMakeColumnOtpCodeNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->bigInteger('tg_id')->nullable()->unique()->after('id');
            $table->string('tg_username')->nullable()->after('second_name');
            $table->string('otp_code')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('tg_id');
            $table->dropColumn('tg_username');
            $table->string('otp_code')->nullable(false)->change();
        });
    }
}
