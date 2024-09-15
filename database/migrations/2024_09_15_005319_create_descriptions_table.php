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
            'descriptions',
            function (Blueprint $table) {
                $table->id();
                $table->string('lang', 2);
                $table->string('name', 75);
                $table->string('description', 1000);
                $table->enum(
                    'type',
                    [
                        'skill',
                        'proj',
                        'cert',
                        'edu',
                        'exp',
                    ]
                );
                $table->timestamps();
                $table->softDeletes();
                $table->morphs('descriptionable');
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
        Schema::dropIfExists('descriptions');
    }
};
