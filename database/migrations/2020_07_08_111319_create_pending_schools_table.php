<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePendingSchoolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pending_schools', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('school_id');
            $table->char('uuid', 36)->index();
            $table->integer('npsn', 20)->autoIncrement(false)->index();
            $table->string('name')->index();
            $table->Text('address');
            $table->integer('province_id', 20)->autoIncrement(false)->index();
            $table->integer('regency_id', 20)->autoIncrement(false)->index();
            $table->integer('district_id', 20)->autoIncrement(false)->index();
            $table->string('village_id')->nullable()->index();
            $table->char('postal_code', 7)->nullable();
            $table->char('phone_number', 16)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('website')->nullable();
            $table->char('curriculum', 12)->nullable();
            $table->string('headmaster', 120)->nullable();
            $table->string('schools_hour')->nullable();
            $table->integer('total_student', 11)->autoIncrement(false)->index();
            $table->char('accreditation', 4)->nullable();
            $table->char('educational_stage', 12)->nullable();
            $table->boolean('status')->nullable();
            $table->boolean('accepted')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pending_schools');
    }
}
