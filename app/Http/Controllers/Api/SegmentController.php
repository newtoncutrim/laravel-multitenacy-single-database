<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Segment;
use Illuminate\Http\JsonResponse;

class SegmentController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => Segment::query()
                ->where('active', true)
                ->orderBy('name')
                ->get(['id', 'name', 'slug', 'description']),
        ]);
    }
}
