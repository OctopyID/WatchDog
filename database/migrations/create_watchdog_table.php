<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * @return void
     */
    public function up() : void
    {
        Schema::create(config('watchdog.tables.roles'), function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create(config('watchdog.tables.abilities'), function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('title')->nullable();
            $table->bigInteger('entity_id')->nullable();
            $table->string('entity_type')->nullable();
            $table->timestamps();
        });

        Schema::create(config('watchdog.tables.permissions'), function (Blueprint $table) {
            $table->id();
            $table->foreignId('ability_id')->constrained();
            $table->morphs('entity');
            $table->timestamps();
        });

        Schema::create(config('watchdog.tables.assigned_roles'), function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained();
            $table->morphs('entity');
            $table->timestamps();
        });
    }

    /**
     * @return void
     */
    public function down() : void
    {
        Schema::dropIfExists(config('watchdog.tables.roles'));
        Schema::dropIfExists(config('watchdog.tables.abilities'));
        Schema::dropIfExists(config('watchdog.tables.permissions'));
        Schema::dropIfExists(config('watchdog.tables.assigned_roles'));
    }
};
