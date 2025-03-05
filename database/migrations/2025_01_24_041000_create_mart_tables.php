<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('mart_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('org_staff_id')->constrained('org_staff')->onDelete('cascade');
            $table->foreignId('asset_id')->nullable()->constrained('mart_assets');
            $table->string('request_type'); // e.g., Asset, Leave, Training, etc.
            $table->date('request_date');
            $table->string('status');
            $table->string('approval_layer_1_status')->default('Pending');
            $table->string('approval_layer_2_status')->default('Pending');
            $table->string('approval_layer_3_status')->default('Pending');
            $table->string('final_status')->default('Pending');
            $table->timestamps();
        });

        Schema::create('mart_request_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained('mart_requests')->onDelete('cascade');
            $table->string('action');
            $table->date('action_date');
            $table->foreignId('performed_by')->constrained('org_staff')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('mart_assets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('asset_type_id')->constrained('mart_asset_types')->onDelete('cascade');
            $table->string('description');
            $table->integer('quantity_available');
            $table->timestamps();
        });

        Schema::create('mart_asset_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mart_requests');
        Schema::dropIfExists('mart_request_history');
        Schema::dropIfExists('mart_assets');
        Schema::dropIfExists('mart_asset_types');
    }
};
