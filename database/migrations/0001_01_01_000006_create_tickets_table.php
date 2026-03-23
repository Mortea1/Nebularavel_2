<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('project_id')->nullable();
            $table->string('priority')->nullable();
            $table->string('status')->default('open');
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->string('created_at_long')->nullable();
            $table->string('creator')->nullable();
            $table->string('type')->nullable();
            $table->string('category')->nullable();
            $table->text('description')->nullable();
            $table->text('steps')->nullable();
            $table->integer('in_contract')->default(0);
            $table->text('comments')->nullable();
            $table->text('attachments')->nullable();
            $table->string('expected')->nullable();
            $table->integer('time_spent')->default(0);
            $table->timestamps();

            $table->foreign('project_id')->references('id')->on('projects')->onDelete('set null');
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
