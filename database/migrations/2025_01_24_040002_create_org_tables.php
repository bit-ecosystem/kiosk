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
        Schema::create('org_staff', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // Foreign key to users table
            $table->date('date_of_birth')->nullable();
            $table->string('staffno');
            $table->date('join_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();
        });

        Schema::create('org_staff_roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('role_name');
            $table->string('description')->nullable();
            $table->string('value');
            $table->timestamps();
        });

        Schema::create('org_staff_staff_role', function (Blueprint $table) {
            $table->foreignId('org_staff_id')->constrained('org_staff')->onDelete('cascade');
            $table->foreignId('org_staff_role_id')->constrained('org_staff_roles')->onDelete('cascade');
            $table->primary(['org_staff_id', 'org_staff_role_id']);
            $table->timestamps();
        });

        Schema::create('org_job_positions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('org_staff_id')->nullable()->constrained('org_staff')->onDelete('cascade'); // Foreign key to staff table
            $table->foreignId('reports_to_id')->nullable()->constrained('org_job_positions')->onDelete('cascade');
            $table->string('unique_id');
            $table->string('title');
            $table->string('description');
            $table->string('emasco')->nullable();
            $table->timestamps();
        });

        Schema::create('org_job_roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('description');
            $table->timestamps();
        });

        Schema::create('org_job_position_job_role', function (Blueprint $table) {
            $table->foreignId('org_job_position_id')->constrained('org_job_positions')->onDelete('cascade');
            $table->foreignId('org_job_role_id')->constrained('org_job_roles')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('org_companies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('description');
            $table->timestamps();
        });

        Schema::create('org_divisions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('description');
            $table->timestamps();
        });

        Schema::create('org_departments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('department');
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::dropIfExists('org_departments');
        Schema::dropIfExists('org_divisions');
        Schema::dropIfExists('org_companies');
        Schema::dropIfExists('org_job_position_job_role');
        Schema::dropIfExists('org_job_roles');
        Schema::dropIfExists('org_job_positions');
        Schema::dropIfExists('org_staff_roles');
        Schema::dropIfExists('org_staff_staff_role');
        Schema::dropIfExists('org_staff');
    }
};
