<?php

namespace App\Actions;

use App\Days;
use App\Models\User;
use Illuminate\Support\Collection;


class RefreshUserTasks
{
    public function handle(User $user): Collection
    {
        $tasks = $user->tasks;
        foreach ($tasks as $task) {
            if ($task->isToday) {
                if ($task->expiry < date('H:i') && $task->status === 'waiting') {
                    $task->update(['status' => 'missed']);
                }
            }
        }
        return $tasks;
    }
}
