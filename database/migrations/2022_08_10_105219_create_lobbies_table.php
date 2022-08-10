<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lobbies', function (Blueprint $table) {
            $table->string('id')->primary()->unique();
            $table->timestamp('allocated_at')->nullable();
            $table->timestamps();
        });
    }
};
