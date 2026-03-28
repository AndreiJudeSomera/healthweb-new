<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Consultation;
use App\Models\PatientRecord;
use App\Models\Prescription;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function index()
    {
        return view('reports.reports', [
            'monthlyTrend'     => $this->monthlyAppointmentsTrend(),
            'statusData'       => $this->appointmentStatusBreakdown(),
            'ageData'          => $this->patientAgeDemographics(),
            'genderData'       => $this->genderDistribution(),
            'diagnosisData'    => $this->topDiagnoses(),
            'medicineData'     => $this->topMedicines(),
            'apptTypeData'     => $this->appointmentTypeDistribution(),
            'doctorApptData'   => $this->appointmentsByDoctor(),
            'conditionsData'   => $this->preExistingConditions(),
            'busiestDaysData'  => $this->busiestDaysOfWeek(),
        ]);
    }

    private function monthlyAppointmentsTrend(): array
    {
        $labels = [];
        $values = [];

        for ($i = 11; $i >= 0; $i--) {
            $date     = Carbon::now()->subMonths($i);
            $labels[] = $date->format('M Y');
            $values[] = Appointment::whereYear('appointment_date', $date->year)
                ->whereMonth('appointment_date', $date->month)
                ->count();
        }

        return ['labels' => $labels, 'values' => $values];
    }

    private function appointmentStatusBreakdown(): array
    {
        $rows = Appointment::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $statuses = ['pending', 'approved', 'completed', 'cancelled'];
        $labels   = [];
        $values   = [];

        foreach ($statuses as $status) {
            $labels[] = ucfirst($status);
            $values[] = $rows[$status] ?? 0;
        }

        return ['labels' => $labels, 'values' => $values];
    }

    private function patientAgeDemographics(): array
    {
        $groups = [
            'Children (0-12)'      => [0, 12],
            'Teens (13-19)'        => [13, 19],
            'Young Adults (20-35)' => [20, 35],
            'Middle-age (36-50)'   => [36, 50],
            'Older Adults (51-59)' => [51, 59],
            'Seniors (60+)'        => [60, 999],
        ];

        $counts = array_fill_keys(array_keys($groups), 0);

        PatientRecord::whereNotNull('date_of_birth')
            ->pluck('date_of_birth')
            ->each(function ($dob) use (&$counts, $groups) {
                $age = Carbon::parse($dob)->age;
                foreach ($groups as $label => [$min, $max]) {
                    if ($age >= $min && $age <= $max) {
                        $counts[$label]++;
                        break;
                    }
                }
            });

        return [
            'labels' => array_keys($counts),
            'values' => array_values($counts),
        ];
    }

    private function genderDistribution(): array
    {
        $rows = PatientRecord::whereNotNull('gender')
            ->where('gender', '!=', '')
            ->select('gender', DB::raw('COUNT(*) as count'))
            ->groupBy('gender')
            ->pluck('count', 'gender')
            ->toArray();

        $labels = [];
        $values = [];
        foreach ($rows as $gender => $count) {
            $labels[] = ucfirst(strtolower($gender));
            $values[] = $count;
        }

        return ['labels' => $labels, 'values' => $values];
    }

    private function topDiagnoses(): array
    {
        $diagnoses = Consultation::whereNotNull('diagnosis')
            ->where('diagnosis', '!=', '')
            ->whereNull('linked_consultation_id')
            ->pluck('diagnosis')
            ->flatMap(fn($d) => array_map('trim', explode(',', $d)))
            ->filter()
            ->map(fn($d) => ucfirst(strtolower($d)))
            ->countBy()
            ->sortDesc()
            ->take(8);

        return [
            'labels' => $diagnoses->keys()->toArray(),
            'values' => $diagnoses->values()->toArray(),
        ];
    }

    private function topMedicines(): array
    {
        $medicines = Prescription::join('prescription_items', 'prescriptions.medicine_id', '=', 'prescription_items.id')
            ->select('prescription_items.medicine_name', DB::raw('COUNT(*) as count'))
            ->groupBy('prescription_items.medicine_name')
            ->orderByDesc('count')
            ->take(8)
            ->pluck('count', 'medicine_name')
            ->toArray();

        return [
            'labels' => array_keys($medicines),
            'values' => array_values($medicines),
        ];
    }

    private function appointmentTypeDistribution(): array
    {
        $types = [
            'consultation'       => 'Consultation',
            'follow-up'          => 'Follow-up',
            'prescription'       => 'Prescription',
            'medical-certificate'=> 'Med. Certificate',
            'referral-letter'    => 'Referral',
            'other'              => 'Other',
        ];

        $rows = Appointment::select('appointment_type', DB::raw('COUNT(*) as count'))
            ->groupBy('appointment_type')
            ->pluck('count', 'appointment_type')
            ->toArray();

        $labels = [];
        $values = [];
        foreach ($types as $key => $label) {
            $labels[] = $label;
            $values[] = $rows[$key] ?? 0;
        }

        return ['labels' => $labels, 'values' => $values];
    }

    private function appointmentsByDoctor(): array
    {
        $rows = DB::table('appointments')
            ->join('users', 'appointments.attended_by', '=', 'users.id')
            ->select(
                'users.username as doctor_name',
                DB::raw('COUNT(*) as count')
            )
            ->whereNotNull('appointments.attended_by')
            ->groupBy('users.id', 'users.username')
            ->orderByDesc('count')
            ->get();

        return [
            'labels' => $rows->pluck('doctor_name')->toArray(),
            'values' => $rows->pluck('count')->toArray(),
        ];
    }

    private function preExistingConditions(): array
    {
        $conditions = [
            'Hypertension' => PatientRecord::where('hypertension', true)->count(),
            'Asthma'       => PatientRecord::where('asthma', true)->count(),
            'Diabetes'     => PatientRecord::where('diabetes', true)->count(),
            'Cancer'       => PatientRecord::where('cancer', true)->count(),
            'Thyroid'      => PatientRecord::where('thyroid', true)->count(),
        ];

        return [
            'labels' => array_keys($conditions),
            'values' => array_values($conditions),
        ];
    }

    private function busiestDaysOfWeek(): array
    {
        $dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        $rows = Appointment::select(
                DB::raw('DAYOFWEEK(appointment_date) as day_num'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('day_num')
            ->orderBy('day_num')
            ->pluck('count', 'day_num')
            ->toArray();

        $labels = [];
        $values = [];
        // DAYOFWEEK: 1=Sunday ... 7=Saturday
        for ($i = 1; $i <= 7; $i++) {
            $labels[] = $dayNames[$i - 1];
            $values[] = $rows[$i] ?? 0;
        }

        return ['labels' => $labels, 'values' => $values];
    }
}
