<?php

namespace App\Actions;

use App\Models\PrayerTimings;
use App\Models\Task;
use App\Models\User;
use App\Prayers;
use Illuminate\Support\Facades\Log;


class CreateStarterPacks
{
    public function createPrayerTasks(User $user): bool
    {

        $prayerTimings = PrayerTimings::find(1);
        $prayers = Prayers::cases();
        $tasks = [];
        foreach ($prayers as $prayer) {
            try {
                $tasks[] = Task::create([
                    'user_id' => $user->id,
                    'title' => $prayer->value,
                    'type' => 'salah',
                    'description' => "Pray $prayer->value on time",
                    'expiry' => $prayerTimings->timings[$prayer->value],
                    'status' => 'waiting'
                ]);
            } catch (\Exception $e) {
                Log::info($tasks);
                Log::error($e->getMessage());
                return false;
            }
        }
        return true;
    }
    public static function generateDefaultsForAllUsers(): void
    {
        $users = User::all();
        foreach ($users as $user) {
            if ($user->tasks->where('title', 'Fajr')->first()) {
                continue;
            }
            $instance = new self();
            $instance->createPrayerTasks($user);
        }
    }
}
