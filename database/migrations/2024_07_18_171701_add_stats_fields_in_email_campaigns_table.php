<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('email_campaigns', function (Blueprint $table) {
            $table->integer('recipients_count')->default(0)->after('moosend_id');
            $table->integer('total_sent')->default(0)->after('recipients_count');
            $table->integer('total_opens')->default(0)->after('total_sent');
            $table->integer('total_bounces')->default(0)->after('total_opens');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('email_campaigns', function (Blueprint $table) {
            $table->dropColumn('recipients_count');
            $table->dropColumn('total_sent');
            $table->dropColumn('total_opens');
            $table->dropColumn('total_bounces');
        });
    }
};
