<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function __invoke()
    {
        $user = Auth::user();

        $announcements = Announcement::published()
            ->with('creator')
            ->visibleForRole($user->role)
            ->latest('publish_at')
            ->get();

        return view('student.announcement', compact('announcements'));
    }
}

