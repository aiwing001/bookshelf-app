<?php

namespace App\Http\Controllers;

use App\Enums\ReadingPlanStatus;
use App\Http\Requests\StoreReadingPlanRequest;
use App\Http\Requests\UpdateReadingPlanRequest;
use App\Models\Book;
use App\Models\ReadingPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReadingPlanController extends Controller
{
    public function index(Request $request): View
    {
        $currentStatus = $request->status;

        $query = ReadingPlan::with('book')
            ->where('user_id', auth()->id());

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $readingPlans = $query->get();

        return view('reading-plans.index', compact(
            'readingPlans',
            'currentStatus',
        ));
    }

    public function create(): View
    {
        $books = Book::all();

        return view('reading-plans.create', compact('books'));
    }

    public function store(StoreReadingPlanRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $data['user_id'] = auth()->id();

        ReadingPlan::create($data);

        return redirect()
            ->route('reading-plans.index')
            ->with('success', '読書計画を登録しました');
    }

    public function edit(ReadingPlan $readingPlan): View
    {
        $this->authorize('update', $readingPlan);

        return view('reading-plans.edit', compact('readingPlan'));
    }

    /**
     * 読書計画を更新し、必要に応じてステータスを再判定する。
     */
    public function update(
        UpdateReadingPlanRequest $request,
        ReadingPlan $readingPlan
    ): RedirectResponse {
        $this->authorize('update', $readingPlan);

        $data = $request->validated();

        if (
            $readingPlan->status === ReadingPlanStatus::Expired
            && $data['target_date'] >= today()->toDateString()
        ) {
            $data['status'] = ReadingPlanStatus::InProgress;
        }

        $readingPlan->update($data);

        return redirect()
            ->route('reading-plans.index')
            ->with('success', '読書計画を更新しました');
    }

    public function destroy(ReadingPlan $readingPlan): RedirectResponse
    {
        $this->authorize('delete', $readingPlan);

        $readingPlan->delete();

        return redirect()
            ->route('reading-plans.index')
            ->with('success', '読書計画を削除しました');
    }

    /**
     * 読書計画を完了状態に更新する。
     */
    public function complete(ReadingPlan $readingPlan): RedirectResponse
    {
        $this->authorize('complete', $readingPlan);

        $readingPlan->update([
            'status' => ReadingPlanStatus::Completed,
            'completed_at' => now(),
        ]);

        return redirect()
            ->route('reading-plans.index')
            ->with('success', '読書計画を完了しました');
    }
}
