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
        foreach ($prayers as $index => $prayer) {
            for ($i = 0; $i < 7; $i++) {
                if ($index === 0) {
                    $this->createTarawehPack($i, $user->id);
                }
                try {
                    $tasks[] = Task::create([
                        'user_id' => $user->id,
                        'title' => $prayer->value,
                        'type' => 'salah',
                        'description' => "Pray $prayer->value on time",
                        'expiry' => $prayerTimings->timings[$prayer->value],
                        'status' => 'waiting',
                        'repeats' => [$i],
                    ]);
                } catch (\Exception $e) {
                    Log::info($tasks);
                    Log::error($e->getMessage());
                    return false;
                }
            }
        }
        return true;
    }
    public static function generateDefaultsForAllUsers(): void
    {
        $users = User::all();
        foreach ($users as $user) {
            if (Task::where(['type' => 'salah', "user_id" => $user->id])->get()) {
                foreach ($user->tasks->where('type', 'salah') as $task) {
                    if ($task->title === 'Fajr' || $task->title === 'Dhuhr' || $task->title === 'Asr' || $task->title === 'Maghrib' || $task->title === 'Isha')
                        $task->delete();
                }
            }
            $instance = new self();
            $instance->createPrayerTasks($user);
        }
    }
    public function createTarawehPack($counter, $id): void
    {
        if (!Task::where(['user_id' => $id, 'title' => "Taraweh"])->whereJsonContains('repeats', $counter)->first()) {
            Task::create([
                'user_id' => $id,
                'title' => 'Taraweh',
                'type' => 'salah',
                'description' => 'Pray Taraweh on time',
                'expiry' => '21:30',
                'status' => 'waiting',
                'repeats' => [$counter],
            ]);
        }
    }
}
