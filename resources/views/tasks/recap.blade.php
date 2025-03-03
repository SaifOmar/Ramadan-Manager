<!-- resources/views/recaps.blade.php -->
@extends('layouts.app')

@section('title', 'Task Recap - Task Manager')

@section('content')
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Task Activity Recap</h1>
            <div class="flex space-x-4">
                <select id="period-filter"
                    class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="7">Last 7 Days</option>
                    <option value="30">Last 30 Days</option>
                    <option value="90">Last 90 Days</option>
                </select>
                <a href="{{ route('home') }}"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Performance Overview -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Task Completion Overview</h2>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-medium text-gray-600">Total Tasks</h3>
                    <p class="text-2xl font-bold text-blue-600">{{ count($recaps) }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-medium text-gray-600">Completed</h3>
                    <p class="text-2xl font-bold text-green-600">{{ count($recaps->where('status', 'done')) }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-medium text-gray-600">Missed</h3>
                    <p class="text-2xl font-bold text-red-600">{{ count($recaps->where('status', 'missed')) }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-medium text-gray-600">Completion Rate</h3>
                    <p class="text-2xl font-bold text-indigo-600">
                        {{ count($recaps) > 0 ? round((count($recaps->where('status', 'done')) / count($recaps)) * 100) : 0 }}%
                    </p>
                </div>
            </div>

            <!-- Completion Progress Bar -->
            <div class="w-full bg-gray-200 rounded-full h-4 mb-6">
                @php
                    $completionRate =
                        count($recaps) > 0 ? (count($recaps->where('status', 'done')) / count($recaps)) * 100 : 0;
                @endphp
                <div class="bg-green-600 h-4 rounded-full" style="width: {{ $completionRate }}%"></div>
            </div>

            <!-- Weekly Trend -->
            <h3 class="text-lg font-medium text-gray-700 mb-3">Weekly Trend</h3>
            <div class="grid grid-cols-7 gap-1 mb-2">
                @php
                    $daysOfWeek = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                @endphp

                @foreach ($daysOfWeek as $day)
                    <div class="text-center text-sm text-gray-600">{{ $day }}</div>
                @endforeach
            </div>

            <div class="grid grid-cols-7 gap-1">
                @for ($i = 6; $i >= 0; $i--)
                    @php
                        $date = now()->subDays($i);
                        $dayRecaps = $recaps->filter(function ($recap) use ($date) {
                            return $recap->created_at->format('Y-m-d') === $date->format('Y-m-d');
                        });
                        $total = count($dayRecaps);
                        $completed = count($dayRecaps->where('status', 'done'));
                        $completionPercentage = $total > 0 ? ($completed / $total) * 100 : 0;

                        if ($completionPercentage >= 80) {
                            $bgColor = 'bg-green-500';
                        } elseif ($completionPercentage >= 50) {
                            $bgColor = 'bg-yellow-500';
                        } elseif ($completionPercentage > 0) {
                            $bgColor = 'bg-red-500';
                        } else {
                            $bgColor = 'bg-gray-200';
                        }
                    @endphp

                    <div class="aspect-square {{ $bgColor }} rounded-md"
                        title="{{ $date->format('M d') }}: {{ $completed }}/{{ $total }} tasks completed">
                    </div>
                @endfor
            </div>
        </div>

        <!-- Task Type Breakdown -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- By Task Type -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Task Types</h2>

                @php
                    $taskTypes = $recaps->groupBy(function ($recap) {
                        return $recap->task->type;
                    });
                @endphp

                <div class="space-y-4">
                    @foreach ($taskTypes as $type => $typeRecaps)
                        @php
                            $typeTotal = count($typeRecaps);
                            $typeCompleted = count($typeRecaps->where('status', 'done'));
                            $typeCompletionRate = $typeTotal > 0 ? ($typeCompleted / $typeTotal) * 100 : 0;

                            $typeColors = [
                                'prayer' => 'blue',
                                'quran' => 'green',
                                'food' => 'yellow',
                                'work' => 'purple',
                                'sleep' => 'indigo',
                                'default' => 'gray',
                            ];

                            $color = $typeColors[$type] ?? $typeColors['default'];
                        @endphp

                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center space-x-2">
                                    <span class="w-3 h-3 rounded-full bg-{{ $color }}-500"></span>
                                    <span class="font-medium text-gray-700">{{ ucfirst($type) }}</span>
                                </div>
                                <span class="text-sm text-gray-600">{{ $typeCompleted }}/{{ $typeTotal }}
                                    ({{ round($typeCompletionRate) }}%)
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-{{ $color }}-500 h-2 rounded-full"
                                    style="width: {{ $typeCompletionRate }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- By Time of Day -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Time of Day Performance</h2>

                @php
                    $timeOfDay = [
                        'morning' => $recaps->filter(function ($recap) {
                            return $recap->task->expiry && Carbon\Carbon::parse($recap->task->expiry)->format('H') < 12;
                        }),
                        'afternoon' => $recaps->filter(function ($recap) {
                            $hour = $recap->task->expiry ? Carbon\Carbon::parse($recap->task->expiry)->format('H') : 0;
                            return $hour >= 12 && $hour < 17;
                        }),
                        'evening' => $recaps->filter(function ($recap) {
                            $hour = $recap->task->expiry ? Carbon\Carbon::parse($recap->task->expiry)->format('H') : 0;
                            return $hour >= 17 && $hour < 21;
                        }),
                        'night' => $recaps->filter(function ($recap) {
                            $hour = $recap->task->expiry ? Carbon\Carbon::parse($recap->task->expiry)->format('H') : 0;
                            return $hour >= 21 || $hour < 5;
                        }),
                    ];
                @endphp

                <div class="space-y-4">
                    @foreach ($timeOfDay as $time => $timeRecaps)
                        @php
                            $timeTotal = count($timeRecaps);
                            $timeCompleted = count($timeRecaps->where('status', 'done'));
                            $timeCompletionRate = $timeTotal > 0 ? ($timeCompleted / $timeTotal) * 100 : 0;

                            $timeIcons = [
                                'morning' =>
                                    '<svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path></svg>',
                                'afternoon' =>
                                    '<svg class="w-5 h-5 text-orange-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12z" clip-rule="evenodd"></path></svg>',
                                'evening' =>
                                    '<svg class="w-5 h-5 text-purple-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" clip-rule="evenodd"></path></svg>',
                                'night' =>
                                    '<svg class="w-5 h-5 text-blue-800" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>',
                            ];
                        @endphp

                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center space-x-2">
                                    {!! $timeIcons[$time] !!}
                                    <span class="font-medium text-gray-700">{{ ucfirst($time) }}</span>
                                </div>
                                <span class="text-sm text-gray-600">{{ $timeCompleted }}/{{ $timeTotal }}
                                    ({{ round($timeCompletionRate) }}%)
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $timeCompletionRate }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Recent Task History -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Recent Task Activity</h2>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Task
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Type
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Time
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($recaps->sortByDesc('created_at')->take(10) as $recap)
                            @php
                                $statusColors = [
                                    'completed' => 'green',
                                    'missed' => 'red',
                                    'waiting' => 'yellow',
                                    'default' => 'gray',
                                ];
                                $statusColor = $statusColors[$recap->status] ?? $statusColors['default'];
                            @endphp

                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $recap->task->title }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $typeColors[$recap->task->type] ?? 'gray' }}-100 text-{{ $typeColors[$recap->task->type] ?? 'gray' }}-800">
                                        {{ ucfirst($recap->task->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $recap->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $recap->task->expiry ? Carbon\Carbon::parse($recap->task->expiry)->format('g:i A') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800">
                                        {{ ucfirst($recap->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    No task activity recorded yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (count($recaps) > 10)
                <div class="mt-4 text-center">
                    <a href="{{ route('recaps.all') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        View All Task History
                    </a>
                </div>
            @endif
        </div>

        <!-- Insights and Suggestions -->
        <div class="bg-white rounded-lg shadow-md p-6 mt-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Insights & Recommendations</h2>

            @php
                $completionRate =
                    count($recaps) > 0 ? (count($recaps->where('status', 'done')) / count($recaps)) * 100 : 0;
                $mostMissedType = $recaps
                    ->where('status', 'missed')
                    ->groupBy(function ($recap) {
                        return $recap->task->type;
                    })
                    ->map->count()
                    ->sortDesc()
                    ->keys()
                    ->first();

                $bestTimeOfDay = collect($timeOfDay)
                    ->map(function ($timeRecaps, $time) {
                        $total = count($timeRecaps);
                        $completed = count($timeRecaps->where('status', 'done'));
                        return [
                            'time' => $time,
                            'total' => $total,
                            'completed' => $completed,
                            'rate' => $total > 0 ? ($completed / $total) * 100 : 0,
                        ];
                    })
                    ->sortByDesc('rate')
                    ->first();
            @endphp

            <div class="space-y-4">
                <!-- Overall Performance -->
                <div class="p-4 bg-gray-50 rounded-lg">
                    <h3 class="font-medium text-gray-800 mb-2">Overall Performance</h3>
                    @if ($completionRate >= 80)
                        <p class="text-sm text-gray-600">
                            <span class="font-medium text-green-600">Excellent work!</span> You're completing
                            {{ round($completionRate) }}% of your tasks, which shows great discipline and commitment.
                        </p>
                    @elseif($completionRate >= 60)
                        <p class="text-sm text-gray-600">
                            <span class="font-medium text-blue-600">Good progress!</span> You're completing
                            {{ round($completionRate) }}% of your tasks. Keep pushing to reach excellence.
                        </p>
                    @elseif($completionRate >= 40)
                        <p class="text-sm text-gray-600">
                            <span class="font-medium text-yellow-600">Room for improvement.</span> You're completing
                            {{ round($completionRate) }}% of your tasks. Try setting more achievable goals or creating
                            reminders.
                        </p>
                    @else
                        <p class="text-sm text-gray-600">
                            <span class="font-medium text-red-600">Needs attention.</span> You're currently completing
                            {{ round($completionRate) }}% of your tasks. Consider revisiting your schedule or task
                            difficulty.
                        </p>
                    @endif
                </div>

                <!-- Areas for Improvement -->
                @if ($mostMissedType)
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <h3 class="font-medium text-gray-800 mb-2">Areas for Improvement</h3>
                        <p class="text-sm text-gray-600">
                            You're missing the most tasks in the <span
                                class="font-medium text-indigo-600">{{ ucfirst($mostMissedType) }}</span> category.
                            Consider adjusting these tasks to make them more achievable or scheduling them at a different
                            time.
                        </p>
                    </div>
                @endif

                <!-- Best Performance Time -->
                @if ($bestTimeOfDay && $bestTimeOfDay['total'] > 0)
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <h3 class="font-medium text-gray-800 mb-2">Optimal Performance Time</h3>
                        <p class="text-sm text-gray-600">
                            You perform best during the <span
                                class="font-medium text-indigo-600">{{ ucfirst($bestTimeOfDay['time']) }}</span>
                            with a {{ round($bestTimeOfDay['rate']) }}% completion rate. Consider scheduling important
                            tasks during this time.
                        </p>
                    </div>
                @endif

                <!-- Next Steps -->
                <div class="p-4 bg-gray-50 rounded-lg">
                    <h3 class="font-medium text-gray-800 mb-2">Next Steps</h3>
                    <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                        <li>Review your most frequently missed tasks and adjust their difficulty or timing</li>
                        <li>Try to maintain consistency by completing similar tasks at the same time each day</li>
                        <li>Set reminders for tasks you typically forget</li>
                        <li>Celebrate your accomplishments and progress!</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for filters -->
    <script>
        document.getElementById('period-filter').addEventListener('change', function() {
            window.location.href = "{{ route('tasks.recap') }}?period=" + this.value;
        });
    </script>
@endsection
