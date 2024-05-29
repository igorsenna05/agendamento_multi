<?php

namespace App\Http\Controllers;

use App\Models\ScheduleSlot;
use App\Models\Location;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class ScheduleSlotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $locations = Location::all();
        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : Carbon::now()->startOfWeek();
        $endDate = $startDate->copy()->endOfWeek();
    
        $query = ScheduleSlot::with('location')
            ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
    
        if ($request->has('location_id')) {
            $query->where('location_id', $request->input('location_id'));
        }
    
        $scheduleSlots = $query->get()->groupBy(function ($date) {
            return Carbon::parse($date->date)->format('Y-m-d');
        });
    
        return view('schedule_slots.index', compact('scheduleSlots', 'locations', 'startDate', 'endDate'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       $locations = Location::all();
       return view('schedule_slots.create', compact('locations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'time' => 'required|date_format:H:i:s',
            'is_available' => 'required|boolean',
            'location_id' => 'required|exists:locations,id',
        ]);

        ScheduleSlot::create($request->all());
        return redirect()->route('schedule_slots.index')->with('success', 'Espaço de agendamento criado com sucesso.');
    }

    public function bulkCreate()
    {
       $locations = Location::all();
        return view('schedule_slots.bulk_create', compact('locations'));
    }
  
    public function bulkStore(Request $request)
    {
        $request->validate([
            'locations' => 'required|array',
            'locations.*' => 'exists:locations,id',
            'specific_days' => 'nullable|string',
            'date_range' => 'nullable|string',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'appointment_duration' => 'required|integer',
            'appointments_per_slot' => 'required|integer',
        ]);

        $dates = collect();

        if ($request->filled('specific_days')) {
            $specific_days = explode(',', $request->input('specific_days'));
            foreach ($specific_days as $day) {
                $dates->push(Carbon::parse($day));
            }
        }

        if ($request->filled('date_range')) {
            [$start_date, $end_date] = explode(' até ', $request->input('date_range'));
            $period = Carbon::parse($start_date)->daysUntil($end_date);
            foreach ($period as $date) {
                $dates->push($date);
            }
        }

        $start_time = Carbon::parse($request->input('start_time'));
        $end_time = Carbon::parse($request->input('end_time'));
        $appointment_duration = intval($request->input('appointment_duration'));
        $appointments_per_slot = $request->input('appointments_per_slot');
        $location_ids = $request->input('locations');

        foreach ($dates as $date) {
            foreach ($location_ids as $location_id) {
                $current_time = $start_time->copy();
                while ($current_time->lessThanOrEqualTo($end_time)) {
                    for ($i = 0; $i < $appointments_per_slot; $i++) {
                        ScheduleSlot::create([
                            'date' => $date->toDateString(),
                            'time' => $current_time->toTimeString(),
                            'is_available' => true,
                            'location_id' => $location_id,
                        ]);
                    }
                    $current_time->addMinutes($appointment_duration);
                }
            }
        }

        return redirect()->route('schedule_slots.index')->with('success', 'Espaços de agendamento criados com sucesso.');
    }
    /**
     * Função para excluir slots de agendamento em lote.
     */
    public function bulkDestroyPrep()
    {
       $locations = Location::all();
        return view('schedule_slots.bulk_destroy', compact('locations'));
    }

  public function bulkDestroy(Request $request)
    {
        $request->validate([
            'locations' => 'required|array',
            'locations.*' => 'exists:locations,id',
            'specific_days' => 'nullable|string',
            'date_range' => 'nullable|string',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
        ]);

        $dates = collect();

        if ($request->filled('specific_days')) {
            $specific_days = explode(',', $request->input('specific_days'));
            foreach ($specific_days as $day) {
                $dates->push(Carbon::parse($day));
            }
        }

        if ($request->filled('date_range')) {
            [$start_date, $end_date] = explode(' até ', $request->input('date_range'));
            $period = Carbon::parse($start_date)->daysUntil($end_date);
            foreach ($period as $date) {
                $dates->push($date);
            }
        }

        $start_time = Carbon::parse($request->input('start_time'));
        $end_time = Carbon::parse($request->input('end_time'));
        $location_ids = $request->input('locations');

        foreach ($dates as $date) {
            foreach ($location_ids as $location_id) {
                $slots = ScheduleSlot::where('date', $date->toDateString())
                    ->where('location_id', $location_id)
                    ->whereBetween('time', [$start_time->toTimeString(), $end_time->toTimeString()])
                    ->get();

                foreach ($slots as $slot) {
                    if (!Schedule::where('slot_id', $slot->id)->exists()) {
                        $slot->delete();
                    }
                }
            }
        }

        return redirect()->route('schedule_slots.index')->with('success', 'Espaços de agendamento excluídos com sucesso.');
    }
  
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ScheduleSlot $scheduleSlot)
    {
        $locations = Location::all();
        return view('schedule_slots.edit', compact('scheduleSlot', 'locations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ScheduleSlot $scheduleSlot)
    {
        $request->validate([
            'date' => 'required|date',
            'time' => 'required|date_format:H:i:s',
            'is_available' => 'required|boolean',
            'location_id' => 'required|exists:locations,id',
        ]);

        $scheduleSlot->update($request->all());
        return redirect()->route('schedule_slots.index')->with('success', 'Espaço de agendamento atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ScheduleSlot $scheduleSlot)
    {
        $scheduleSlot->delete();
        return redirect()->route('schedule_slots.index')->with('success', 'Espaço de agendamento excluído com sucesso.');
    }
}
