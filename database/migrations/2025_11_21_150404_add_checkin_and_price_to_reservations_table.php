<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            if (!Schema::hasColumn('reservations', 'checkin_code')) {
                $table->string('checkin_code')->nullable()->after('status');
            }

            if (!Schema::hasColumn('reservations', 'checked_in_at')) {
                $table->timestamp('checked_in_at')->nullable()->after('checkin_code');
            }

            if (!Schema::hasColumn('reservations', 'total_price')) {
                $table->decimal('total_price', 12, 2)->default(0)->after('checked_in_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            if (Schema::hasColumn('reservations', 'checkin_code')) {
                $table->dropColumn('checkin_code');
            }

            if (Schema::hasColumn('reservations', 'checked_in_at')) {
                $table->dropColumn('checked_in_at');
            }

            if (Schema::hasColumn('reservations', 'total_price')) {
                $table->dropColumn('total_price');
            }
        });
    }
};
