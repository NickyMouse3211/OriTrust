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
        Schema::table('users', function (Blueprint $table) {
            $table->string('user_code', 255)->nullable()->after('id');

            $table->string('phone', 30)->nullable()->after('email');

            $table->string('avatar', 255)->nullable()->after('phone');

            $table->enum('gender', [
                'male',
                'female',
                'other',
            ])->nullable()->after('avatar');

            $table->date('birth_date')->nullable()->after('gender');

            $table->text('address')->nullable()->after('birth_date');

            $table->boolean('is_active')
                ->default(true)
                ->after('address');

            $table->timestamp('last_login_at')
                ->nullable()
                ->after('remember_token');

            $table->string('last_login_ip', 45)
                ->nullable()
                ->after('last_login_at');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unique('user_code');

            $table->string('user_code')
                ->nullable(false)
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['user_code']);

            $table->dropColumn([
                'user_code',
                'phone',
                'avatar',
                'gender',
                'birth_date',
                'address',
                'is_active',
                'last_login_at',
                'last_login_ip',
            ]);
        });
    }
};
