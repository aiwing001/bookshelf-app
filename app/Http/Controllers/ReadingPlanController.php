<?php

namespace App\Http\Controllers;

use App\Models\ReadingPlan;
use Illuminate\Http\Request;

class ReadingPlanController extends Controller
{
    public function index(Request $request)
    {
        $currentStatus = $request->status;

        $readingPlans = ReadingPlan::with('book')->paginate(10);

        return view('reading-plans.index', compact(
            'readingPlans',
            'currentStatus',
        ));
    }
}
