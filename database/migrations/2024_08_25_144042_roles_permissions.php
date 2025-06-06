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
    public function up(): void
    {
        Schema::create(
            'roles_permissions',
            function (Blueprint $table) {
                $table->id();
                $table->bigInteger('role_id')->unsigned();
                $table->foreign('role_id')->references('id')->on('roles')
                    ->onDelete('cascade')->onUpdate('cascade');
                $table->bigInteger('permission_id')->unsigned();
                $table->foreign('permission_id')->references('id')->on('permissions')
                    ->onDelete('cascade')->onUpdate('cascade');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('roles_permissions');
    }
};
