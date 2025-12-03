<?php

namespace App\Http\Controllers\Head;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    private array $audienceOptions = ['student', 'faculty', 'assistant', 'headlibrarian'];

    public function index()
    {
        $announcements = Announcement::with('creator')->latest('publish_at')->latest()->get();

        $stats = [
            'active' => Announcement::active()->count(),
            'expired' => Announcement::expired()->count(),
        ];

        return view('head.announcement', [
            'announcements' => $announcements,
            'stats' => $stats,
            'audienceOptions' => $this->audienceOptions,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        Announcement::create(array_merge($data, [
            'created_by' => Auth::id(),
        ]));

        return back()->with('success', 'Announcement created successfully.');
    }

    public function update(Request $request, Announcement $announcement)
    {
        $data = $this->validatedData($request);
        $announcement->update($data);

        return back()->with('success', 'Announcement updated successfully.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return back()->with('success', 'Announcement deleted successfully.');
    }

    private function validatedData(Request $request): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'audience' => ['nullable', 'array'],
            'audience.*' => ['in:' . implode(',', $this->audienceOptions)],
            'status' => ['required', 'in:draft,scheduled,published,archived'],
            'publish_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after_or_equal:publish_at'],
        ]);

        if (empty($validated['audience'])) {
            $validated['audience'] = null;
        }

        return $validated;
    }
}

