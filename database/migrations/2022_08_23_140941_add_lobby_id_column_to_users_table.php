<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('lobby_id')->after('id');

            $table->unique(['lobby_id', 'name']);
            $table->foreign('lobby_id')->references('id')->on('lobbies');
        });
    }
};
