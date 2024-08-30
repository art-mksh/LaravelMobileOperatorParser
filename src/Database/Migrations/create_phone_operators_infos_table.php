<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhoneOperatorsInfosTable extends Migration
{
    public function up()
    {
        Schema::create('phone_operators_infos', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->string('operator');
            $table->string('region');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('phone_operators_infos');
    }
}