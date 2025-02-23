<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Collection;


class RefreshUserTasks
{
    public function handle(User $user): Collection
    {
        $tasks = $user->tasks;
        foreach ($tasks as $task) {
            if ($task->expiry < date('H:i') && $task->status === 'waiting') {
                $task->update(['status' => 'missed']);
            }
        }
        return $tasks;
    }
}
