<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FeedbackController extends Controller
{
    public function index(): View
    {
        return view('admin.feedback.index', [
            'feedback' => Feedback::query()->latest()->paginate(15),
        ]);
    }

    /** Tampilkan / sembunyikan masukan dari halaman publik. */
    public function update(Feedback $feedback): RedirectResponse
    {
        $feedback->update(['is_approved' => ! $feedback->is_approved]);

        return back()->with('success', $feedback->is_approved ? 'Masukan ditampilkan.' : 'Masukan disembunyikan.');
    }

    public function destroy(Feedback $feedback): RedirectResponse
    {
        $feedback->delete();

        return back()->with('success', 'Masukan dihapus.');
    }
}
