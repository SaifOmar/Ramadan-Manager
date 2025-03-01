<?php

use App\Models\PrayerTimings;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('prayer_timings', function (Blueprint $table) {
            $table->id();
            $table->json('timings');
            $table->timestamps();
        });

        $this->seedPrayerTimings();
    }

    public function seedPrayerTimings(): void
    {
        $apiTimigns = Http::get('http://api.aladhan.com/v1/timingsByCity', [
            'city' => 'Cairo',
            'country' => 'Egypt',
        ])->json()['data']['timings'];
        $prayerTimings = new PrayerTimings();
        $prayerTimings->timings = $apiTimigns;
        $prayerTimings->save();
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prayer_timings');
    }
};
