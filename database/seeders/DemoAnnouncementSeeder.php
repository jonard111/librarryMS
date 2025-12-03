<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DemoAnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        $creator = User::first();

        Announcement::truncate();

        Announcement::create([
            'title' => 'Library Closed on Holiday',
            'body' => "The library will be closed on Oct 1 due to a college holiday. Please return borrowed items in advance and plan your research schedule accordingly.\n\nThank you!",
            'audience' => null,
            'status' => 'published',
            'publish_at' => Carbon::now()->subDay(),
            'expires_at' => Carbon::now()->addWeeks(2),
            'created_by' => optional($creator)->userId,
        ]);

        Announcement::create([
            'title' => 'Faculty Research Updates',
            'body' => "Faculty members can now submit their research updates through the LMS portal. Please complete submissions before the end of the month.",
            'audience' => ['faculty'],
            'status' => 'published',
            'publish_at' => Carbon::now()->subDays(2),
            'expires_at' => Carbon::now()->addMonth(),
            'created_by' => optional($creator)->userId,
        ]);

        Announcement::create([
            'title' => 'Assistant Onboarding Session',
            'body' => "Library assistants are invited to the onboarding session this Friday at 2 PM in the main hall.",
            'audience' => ['assistant'],
            'status' => 'published',
            'publish_at' => Carbon::now()->subHours(6),
            'expires_at' => Carbon::now()->addWeek(),
            'created_by' => optional($creator)->userId,
        ]);
    }
}
