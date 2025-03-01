<?php

use App\Models\Task;
use App\Models\User;
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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string("title");
            $table->text("description")->nullable();
            $table->time('expiry');
            $table->enum('type', ['salah', 'quran', 'food', 'sleep', 'work']);
            $table->enum('status', ["done", "waiting", "missed"]);
            $table->timestamps();
        });
        User::create([
            'name' => 'Ahmed',
            'email' => 'test@test.com',
            'password' => bcrypt('testtest'),
        ]);

        Task::create([
            'title' => 'Fajr',
            'type' => 'salah',
            'status' => 'waiting',
            'expiry' => '05:00',
            'user_id' => 1,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
