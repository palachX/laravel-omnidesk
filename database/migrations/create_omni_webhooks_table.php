<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('omni_webhooks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->nullable()->comment('Name your webhook');
            $table->string('channel')->comment('Channel before create your custom channel');
            $table->string('api_key')->nullable()->comment('Api key if you need authorization');
            $table->timestamps();

            $table->comment('Table for save webhook');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('omni_webhooks');
    }
};
