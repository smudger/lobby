<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('aggregate_id');
            $table->string('type');
            $table->json('body');
            $table->dateTime('occurred_at');

            $table->index('aggregate_id');
        });
    }
};
