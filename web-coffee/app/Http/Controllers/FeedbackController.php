<?php

namespace App\Http\Controllers;

use App\Http\Requests\FeedbackRequest;
use App\Models\Feedback;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FeedbackController extends Controller
{
    public function index(): View
    {
        $approved = Feedback::query()->approved();

        return view('feedback.index', [
            'feedback' => (clone $approved)->latest()->paginate(10),
            'average' => round((float) (clone $approved)->avg('rating'), 1),
            'total' => (clone $approved)->count(),
        ]);
    }

    public function store(FeedbackRequest $request): RedirectResponse
    {
        Feedback::create([
            ...$request->validated(),
            'user_id' => $request->user()?->id,
        ]);

        return redirect()->route('feedback.index')->with('success', 'Terima kasih, masukanmu sudah terkirim.');
    }
}
