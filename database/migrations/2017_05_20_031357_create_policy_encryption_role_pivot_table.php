<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePolicyEncryptionRolePivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('policy_encryption_role', function (Blueprint $table) {
            $table->unsignedInteger('policy_encryption_id')->index();
            $table->foreign('policy_encryption_id')->references('id')->on('policy_encryptions')->onDelete('cascade');
            $table->unsignedInteger('role_id')->index();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->boolean('authorized')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('policy_encryption_role');
    }
}
