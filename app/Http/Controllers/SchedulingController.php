<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Location;
use App\Models\ScheduleSlot;
use App\Models\Schedule;
use App\Models\DecisionTree;
use Illuminate\Support\Str;

class SchedulingController extends Controller
{
    public function index(Request $request)
    {
        // Verifica o referer
        $referer = $request->headers->get('referer');
        if (!$referer || !str_contains($referer, route('decision_tree.user'))) {
            return redirect()->route('decision_tree.user');
        }
        $services = Service::all();
        $locations = Location::all();
        $slots = [];
        $nodeId = $request->query('node_id', '');
        $node = null;
        if ($nodeId) {
            $node = DecisionTree::find($nodeId);
            if (!$node) {
                return redirect()->route('decision_tree.user')->with('error', 'Invalid node ID.');
            }
        }

        if ($request->ajax()) {
            if ($request->has('location_id') && $request->has('date')) {
                $slots = ScheduleSlot::where('location_id', $request->location_id)
                    ->whereDate('date', $request->date)
                    ->where('is_available', true)
                    ->get();
                return response()->json(['slots' => $slots]);
            } elseif ($request->has('location_id')) {
                $dates = ScheduleSlot::where('location_id', $request->location_id)
                    ->where('is_available', true)
                    ->select('date')
                    ->distinct()
                    ->get()
                    ->pluck('date');
                return response()->json(['dates' => $dates]);
            }
        }

        return view('scheduling.index', compact('services', 'locations', 'slots', 'node'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_name' => 'required|string|max:255',
            'user_cpf' => 'required|string|max:255',
            'user_insc' => 'required|string|max:255',
            'slot_id' => 'required|exists:schedule_slots,id',
            'service_type' => 'required|string|max:255',
            'location_id' => 'required|exists:locations,id',
        ]);

        $schedule = Schedule::create([
            'user_name' => $request->user_name,
            'user_cpf' => $request->user_cpf,
            'user_insc' => $request->user_insc,
            'slot_id' => $request->slot_id,
            'service_type' => $request->service_type,
            'location_id' => $request->location_id,
            'confirmation_token' => Str::random(32),
        ]);
        $location=Location::find($schedule->location_id);
        $slot = ScheduleSlot::find($schedule->slot_id);

        return view('scheduling.schedule', compact('schedule','location','slot'));
    }
}
