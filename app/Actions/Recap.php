<?php

namespace App\Actions;

use App\Models\Task;
use App\Models\User;
use App\Models\Recap as RecapModel;
use Illuminate\Support\Facades\Log;


class Recap
{
    public function save(User $user, Task $task): void
    {
        try {
            RecapModel::create(
                [
                    'user_id' => $user->id,
                    'task_id' => $task->id,
                    'status' => $task->status,
                ]
            );
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }
}
