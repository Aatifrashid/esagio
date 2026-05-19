<?php

namespace App\Livewire\Crm;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

class CalendarView extends Component
{
    public string $currentDateString;
    public string $viewMode = 'month';
    public array $appointments = [];
    public bool $showCreateModal = false;
    public string $selectedDate = '';

    // Form fields
    public string $title = '';
    public ?int $patientId = null;
    public string $patientSearch = '';
    public string $patientName = '';
    public string $startsAt = '';
    public string $endsAt = '';
    public string $appointmentType = 'consultation';
    public string $description = '';
    public ?int $assignedTo = null;
    public ?int $editingAppointmentId = null;

    public function mount(): void
    {
        $this->currentDateString = now()->format('Y-m-d');
        $this->loadAppointments();
    }

    public function getCurrentDateProperty(): Carbon
    {
        return Carbon::parse($this->currentDateString);
    }

    public function loadAppointments(): void
    {
        $currentDate = $this->currentDate;

        if ($this->viewMode === 'month') {
            $start = $currentDate->copy()->startOfMonth()->startOfWeek(Carbon::MONDAY);
            $end = $currentDate->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);
        } elseif ($this->viewMode === 'week') {
            $start = $currentDate->copy()->startOfWeek(Carbon::MONDAY);
            $end = $currentDate->copy()->endOfWeek(Carbon::SUNDAY);
        } else {
            $start = $currentDate->copy()->startOfDay();
            $end = $currentDate->copy()->endOfDay();
        }

        $this->appointments = Appointment::query()
            ->where('clinic_id', auth()->user()->clinic_id)
            ->where('starts_at', '>=', $start)
            ->where('starts_at', '<=', $end)
            ->orderBy('starts_at')
            ->get()
            ->map(fn ($a) => [
                'id' => $a->id,
                'title' => $a->title,
                'starts_at' => $a->starts_at->toDateTimeString(),
                'ends_at' => $a->ends_at->toDateTimeString(),
                'status' => $a->status,
                'type' => $a->type,
                'colour' => $a->colour,
                'patient_id' => $a->patient_id,
            ])
            ->toArray();
    }

    public function previousPeriod(): void
    {
        $currentDate = $this->currentDate;

        $this->currentDateString = match ($this->viewMode) {
            'month' => $currentDate->subMonth()->format('Y-m-d'),
            'week' => $currentDate->subWeek()->format('Y-m-d'),
            'day' => $currentDate->subDay()->format('Y-m-d'),
        };

        $this->loadAppointments();
    }

    public function nextPeriod(): void
    {
        $currentDate = $this->currentDate;

        $this->currentDateString = match ($this->viewMode) {
            'month' => $currentDate->addMonth()->format('Y-m-d'),
            'week' => $currentDate->addWeek()->format('Y-m-d'),
            'day' => $currentDate->addDay()->format('Y-m-d'),
        };

        $this->loadAppointments();
    }

    public function goToToday(): void
    {
        $this->currentDateString = now()->format('Y-m-d');
        $this->loadAppointments();
    }

    public function setViewMode(string $mode): void
    {
        $this->viewMode = $mode;
        $this->loadAppointments();
    }

    public function openCreateModal(string $date = ''): void
    {
        $this->resetForm();

        if ($date) {
            if (strlen($date) === 10) {
                $this->startsAt = $date . 'T09:00';
                $this->endsAt = $date . 'T10:00';
            } else {
                $parsed = Carbon::parse($date);
                $this->startsAt = $parsed->format('Y-m-d\TH:i');
                $this->endsAt = $parsed->addHour()->format('Y-m-d\TH:i');
            }
        }

        $this->selectedDate = $date;
        $this->showCreateModal = true;
    }

    public function createAppointment(): void
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'startsAt' => 'required|date',
            'endsAt' => 'required|date|after:startsAt',
            'appointmentType' => 'required|in:consultation,follow_up,treatment,other',
            'patientId' => 'nullable|exists:patients,id',
        ]);

        Appointment::create([
            'clinic_id' => auth()->user()->clinic_id,
            'patient_id' => $this->patientId ?: null,
            'assigned_to' => $this->assignedTo ?: auth()->id(),
            'created_by' => auth()->id(),
            'title' => $this->title,
            'description' => $this->description ?: null,
            'starts_at' => Carbon::parse($this->startsAt),
            'ends_at' => Carbon::parse($this->endsAt),
            'status' => 'scheduled',
            'type' => $this->appointmentType,
        ]);

        $this->showCreateModal = false;
        $this->resetForm();
        $this->loadAppointments();
    }

    public function openEditModal(int $id): void
    {
        $appointment = Appointment::where('clinic_id', auth()->user()->clinic_id)->findOrFail($id);

        $this->resetForm();
        $this->editingAppointmentId = $appointment->id;
        $this->title = $appointment->title;
        $this->startsAt = $appointment->starts_at->format('Y-m-d\TH:i');
        $this->endsAt = $appointment->ends_at->format('Y-m-d\TH:i');
        $this->appointmentType = $appointment->type ?? 'consultation';
        $this->description = $appointment->description ?? '';
        $this->assignedTo = $appointment->assigned_to;

        if ($appointment->patient_id) {
            $patient = Patient::find($appointment->patient_id);
            if ($patient) {
                $this->patientId = $patient->id;
                $this->patientName = $patient->first_name . ' ' . $patient->last_name;
            }
        }

        $this->showCreateModal = true;
    }

    public function updateAppointment(): void
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'startsAt' => 'required|date',
            'endsAt' => 'required|date|after:startsAt',
            'appointmentType' => 'required|in:consultation,follow_up,treatment,other',
            'patientId' => 'nullable|exists:patients,id',
        ]);

        $appointment = Appointment::where('clinic_id', auth()->user()->clinic_id)
            ->findOrFail($this->editingAppointmentId);

        $appointment->update([
            'title' => $this->title,
            'patient_id' => $this->patientId ?: null,
            'assigned_to' => $this->assignedTo ?: auth()->id(),
            'description' => $this->description ?: null,
            'starts_at' => Carbon::parse($this->startsAt),
            'ends_at' => Carbon::parse($this->endsAt),
            'type' => $this->appointmentType,
        ]);

        $this->showCreateModal = false;
        $this->resetForm();
        $this->loadAppointments();
    }

    public function deleteAppointment(): void
    {
        if ($this->editingAppointmentId) {
            Appointment::where('clinic_id', auth()->user()->clinic_id)
                ->where('id', $this->editingAppointmentId)
                ->delete();

            $this->showCreateModal = false;
            $this->resetForm();
            $this->loadAppointments();
        }
    }

    public function selectPatient(int $id): void
    {
        $patient = Patient::find($id);
        $this->patientId = $id;
        $this->patientName = $patient ? $patient->first_name . ' ' . $patient->last_name : '';
        $this->patientSearch = '';
    }

    public function clearPatient(): void
    {
        $this->patientId = null;
        $this->patientName = '';
        $this->patientSearch = '';
    }

    #[Computed]
    public function patientResults(): Collection
    {
        if (strlen($this->patientSearch) < 1) {
            return collect();
        }

        return Patient::where('clinic_id', auth()->user()->clinic_id)
            ->where(function ($q) {
                $q->where('first_name', 'like', '%' . $this->patientSearch . '%')
                  ->orWhere('last_name', 'like', '%' . $this->patientSearch . '%')
                  ->orWhere('email', 'like', '%' . $this->patientSearch . '%');
            })
            ->orderBy('first_name')
            ->limit(10)
            ->get(['id', 'first_name', 'last_name', 'email']);
    }

    private function resetForm(): void
    {
        $this->editingAppointmentId = null;
        $this->title = '';
        $this->patientId = null;
        $this->patientSearch = '';
        $this->patientName = '';
        $this->startsAt = '';
        $this->endsAt = '';
        $this->appointmentType = 'consultation';
        $this->description = '';
        $this->assignedTo = null;
    }

    #[Computed]
    public function getCalendarDays(): array
    {
        $currentDate = $this->currentDate;
        $startOfMonth = $currentDate->copy()->startOfMonth();
        $endOfMonth = $currentDate->copy()->endOfMonth();
        $startOfGrid = $startOfMonth->copy()->startOfWeek(Carbon::MONDAY);

        $days = [];
        $date = $startOfGrid->copy();
        $today = now()->format('Y-m-d');

        for ($i = 0; $i < 42; $i++) {
            $dateStr = $date->format('Y-m-d');
            $dayAppointments = array_filter($this->appointments, function ($a) use ($dateStr) {
                return Carbon::parse($a['starts_at'])->format('Y-m-d') === $dateStr;
            });

            $days[] = [
                'date' => $dateStr,
                'dayNumber' => $date->day,
                'isCurrentMonth' => $date->month === $currentDate->month,
                'isToday' => $dateStr === $today,
                'appointments' => array_values($dayAppointments),
            ];

            $date->addDay();
        }

        return $days;
    }

    #[Computed]
    public function getWeekDays(): array
    {
        $currentDate = $this->currentDate;
        $startOfWeek = $currentDate->copy()->startOfWeek(Carbon::MONDAY);
        $today = now()->format('Y-m-d');

        $days = [];
        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i);
            $days[] = [
                'date' => $date->format('Y-m-d'),
                'dayName' => $date->format('D'),
                'dayNumber' => $date->day,
                'isToday' => $date->format('Y-m-d') === $today,
            ];
        }

        return $days;
    }

    #[Computed]
    public function headerLabel(): string
    {
        $currentDate = $this->currentDate;

        return match ($this->viewMode) {
            'month' => $currentDate->format('F Y'),
            'week' => $currentDate->copy()->startOfWeek(Carbon::MONDAY)->format('M d') . ' - ' . $currentDate->copy()->endOfWeek(Carbon::SUNDAY)->format('M d, Y'),
            'day' => $currentDate->format('l, F j, Y'),
        };
    }

    public function render()
    {
        return view('livewire.crm.calendar-view');
    }
}
