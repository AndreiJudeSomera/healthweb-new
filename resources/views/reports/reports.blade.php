@extends('layouts.app')

@section('title', 'Data Visualization')

@section('content')
<div class="flex flex-col gap-4" x-data="{ showAll: false }">

    <h1 class="text-xl font-bold text-blue-950"></h1>

    {{-- Row 1: Appointment Overview --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        {{-- Monthly Appointments Trend --}}
        <div class="bg-white p-5 rounded-md shadow flex flex-col">
            <div class="flex items-start justify-between mb-1">
                <h2 class="font-bold text-base text-blue-950">Monthly Appointments Trend</h2>
                <a href="{{ route('appointments.index') }}" class="text-xs text-blue-600 hover:underline flex-shrink-0 ms-2 mt-0.5">View Appointments →</a>
            </div>
            <p class="text-xs text-gray-400 mb-4">Last 12 months</p>
            <canvas id="monthlyTrendChart" class="max-h-[240px]"></canvas>
        </div>

        {{-- Appointment Status Breakdown --}}
        <div class="bg-white p-5 rounded-md shadow flex flex-col items-center">
            <div class="flex items-start justify-between w-full mb-1">
                <h2 class="font-bold text-base text-blue-950">Appointment Status Breakdown</h2>
                <a href="{{ route('appointments.index') }}" class="text-xs text-blue-600 hover:underline flex-shrink-0 ms-2 mt-0.5">View Appointments →</a>
            </div>
            <p class="text-xs text-gray-400 mb-4 self-start">All time</p>
            <canvas id="statusChart" class="max-h-[240px]"></canvas>
        </div>

    </div>

    {{-- Row 2: Patient Demographics --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        {{-- Patient Age Demographics --}}
        <div class="bg-white p-5 rounded-md shadow flex flex-col">
            <div class="flex items-start justify-between mb-1">
                <h2 class="font-bold text-base text-blue-950">Patient Age Demographics</h2>
                <a href="{{ route('patients.index') }}" class="text-xs text-blue-600 hover:underline flex-shrink-0 ms-2 mt-0.5">View Patients →</a>
            </div>
            <p class="text-xs text-gray-400 mb-4">Registered patients by age group</p>
            <canvas id="ageChart" class="max-h-[240px]"></canvas>
        </div>

        {{-- Gender Distribution --}}
        <div class="bg-white p-5 rounded-md shadow flex flex-col items-center">
            <div class="flex items-start justify-between w-full mb-1">
                <h2 class="font-bold text-base text-blue-950">Patient Gender Distribution</h2>
                <a href="{{ route('patients.index') }}" class="text-xs text-blue-600 hover:underline flex-shrink-0 ms-2 mt-0.5">View Patients →</a>
            </div>
            <p class="text-xs text-gray-400 mb-4 self-start">Registered patients by gender</p>
            <canvas id="genderChart" class="max-h-[240px]"></canvas>
        </div>

    </div>

    {{-- Rows 3–5: Extended charts (hidden initially) --}}
    <div x-show="showAll" x-cloak class="flex flex-col gap-4">

        {{-- Row 3: Clinical Insights --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            {{-- Top Diagnoses --}}
            <div class="bg-white p-5 rounded-md shadow flex flex-col">
                <div class="flex items-start justify-between mb-1">
                    <h2 class="font-bold text-base text-blue-950">Top Diagnoses</h2>
                    <a href="{{ route('patients.index') }}" class="text-xs text-blue-600 hover:underline flex-shrink-0 ms-2 mt-0.5">View Patients →</a>
                </div>
                <p class="text-xs text-gray-400 mb-4">Most frequent from consultations</p>
                <canvas id="diagnosisChart" class="max-h-[240px]"></canvas>
            </div>

            {{-- Top Prescribed Medicines --}}
            <div class="bg-white p-5 rounded-md shadow flex flex-col">
                <div class="flex items-start justify-between mb-1">
                    <h2 class="font-bold text-base text-blue-950">Top Prescribed Medicines</h2>
                    <a href="{{ route('patients.index') }}" class="text-xs text-blue-600 hover:underline flex-shrink-0 ms-2 mt-0.5">View Patients →</a>
                </div>
                <p class="text-xs text-gray-400 mb-4">Most frequently prescribed</p>
                <canvas id="medicineChart" class="max-h-[240px]"></canvas>
            </div>

        </div>

        {{-- Row 4: Appointment Breakdown --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            {{-- Appointment Type Distribution --}}
            <div class="bg-white p-5 rounded-md shadow flex flex-col items-center">
                <div class="flex items-start justify-between w-full mb-1">
                    <h2 class="font-bold text-base text-blue-950">Appointment Type Distribution</h2>
                    <a href="{{ route('appointments.index') }}" class="text-xs text-blue-600 hover:underline flex-shrink-0 ms-2 mt-0.5">View Appointments →</a>
                </div>
                <p class="text-xs text-gray-400 mb-4 self-start">By service type, all time</p>
                <canvas id="apptTypeChart" class="max-h-[240px]"></canvas>
            </div>

            {{-- Appointments by Doctor --}}
            <div class="bg-white p-5 rounded-md shadow flex flex-col">
                <div class="flex items-start justify-between mb-1">
                    <h2 class="font-bold text-base text-blue-950">Appointments by Doctor</h2>
                    <a href="{{ route('appointments.index') }}" class="text-xs text-blue-600 hover:underline flex-shrink-0 ms-2 mt-0.5">View Appointments →</a>
                </div>
                <p class="text-xs text-gray-400 mb-4">Total appointments handled per doctor</p>
                <canvas id="doctorApptChart" class="max-h-[240px]"></canvas>
            </div>

        </div>

        {{-- Row 5: Patient Health Profile --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            {{-- Pre-existing Conditions --}}
            <div class="bg-white p-5 rounded-md shadow flex flex-col">
                <div class="flex items-start justify-between mb-1">
                    <h2 class="font-bold text-base text-blue-950">Pre-existing Conditions Prevalence</h2>
                    <a href="{{ route('patients.index') }}" class="text-xs text-blue-600 hover:underline flex-shrink-0 ms-2 mt-0.5">View Patients →</a>
                </div>
                <p class="text-xs text-gray-400 mb-4">Number of patients with each condition</p>
                <canvas id="conditionsChart" class="max-h-[240px]"></canvas>
            </div>

            {{-- Busiest Days of the Week --}}
            <div class="bg-white p-5 rounded-md shadow flex flex-col">
                <div class="flex items-start justify-between mb-1">
                    <h2 class="font-bold text-base text-blue-950">Busiest Days of the Week</h2>
                    <a href="{{ route('appointments.index') }}" class="text-xs text-blue-600 hover:underline flex-shrink-0 ms-2 mt-0.5">View Appointments →</a>
                </div>
                <p class="text-xs text-gray-400 mb-4">Total appointments per day, all time</p>
                <canvas id="busiestDaysChart" class="max-h-[240px]"></canvas>
            </div>

        </div>

    </div>

    {{-- See All / See Less button --}}
    <div class="flex justify-center">
        <button
            class="flex items-center gap-2 px-5 py-2 text-sm font-medium text-blue-950 border border-blue-950 rounded-md hover:bg-blue-950 hover:text-white transition-colors"
            @click="showAll = !showAll; if (showAll) $nextTick(() => {
                chartDiagnosis.resize(); chartMedicine.resize();
                chartApptType.resize(); chartDoctorAppt.resize();
                chartConditions.resize(); chartBusiestDays.resize();
            })">
            <span x-text="showAll ? 'See Less' : 'See All Charts'"></span>
            <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': showAll }"></i>
        </button>
    </div>

</div>

<script>
    const navy   = '#111c44';
    const blue   = '#4f86c6';
    const green  = '#2ecc40';
    const red    = '#ff4136';
    const orange = '#ff851b';
    const purple = '#b10dc9';
    const teal   = '#39cccc';
    const pink   = '#f06292';
    const yellow = '#f59e0b';

    // 1. Monthly Appointments Trend
    new Chart(document.getElementById('monthlyTrendChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlyTrend['labels']) !!},
            datasets: [{
                label: 'Appointments',
                data: {!! json_encode($monthlyTrend['values']) !!},
                borderColor: navy,
                backgroundColor: 'rgba(17,28,68,0.08)',
                fill: true,
                tension: 0.3,
                pointRadius: 4,
                pointBackgroundColor: navy,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });

    // 2. Appointment Status Breakdown
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($statusData['labels']) !!},
            datasets: [{
                data: {!! json_encode($statusData['values']) !!},
                backgroundColor: [orange, blue, green, red],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom', labels: { font: { size: 12 }, padding: 16 } }
            }
        }
    });

    // 3. Patient Age Demographics
    new Chart(document.getElementById('ageChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($ageData['labels']) !!},
            datasets: [{
                label: 'Patients',
                data: {!! json_encode($ageData['values']) !!},
                backgroundColor: [teal, blue, navy, purple, orange, red],
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { x: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });

    // 4. Gender Distribution
    const genderLabels = {!! json_encode($genderData['labels']) !!};
    const genderColors = genderLabels.map(l => {
        switch (l.toLowerCase()) {
            case 'male':   return blue;
            case 'female': return pink;
            default:       return teal;
        }
    });
    new Chart(document.getElementById('genderChart'), {
        type: 'pie',
        data: {
            labels: genderLabels,
            datasets: [{
                data: {!! json_encode($genderData['values']) !!},
                backgroundColor: genderColors,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom', labels: { font: { size: 12 }, padding: 16 } }
            }
        }
    });

    // 5. Top Diagnoses
    const chartDiagnosis = new Chart(document.getElementById('diagnosisChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($diagnosisData['labels']) !!},
            datasets: [{
                label: 'Cases',
                data: {!! json_encode($diagnosisData['values']) !!},
                backgroundColor: navy,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });

    // 6. Top Prescribed Medicines
    const chartMedicine = new Chart(document.getElementById('medicineChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($medicineData['labels']) !!},
            datasets: [{
                label: 'Prescriptions',
                data: {!! json_encode($medicineData['values']) !!},
                backgroundColor: teal,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });

    // 7. Appointment Type Distribution
    const chartApptType = new Chart(document.getElementById('apptTypeChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($apptTypeData['labels']) !!},
            datasets: [{
                data: {!! json_encode($apptTypeData['values']) !!},
                backgroundColor: [navy, blue, teal, orange, purple, pink],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom', labels: { font: { size: 12 }, padding: 16 } }
            }
        }
    });

    // 8. Appointments by Doctor
    const chartDoctorAppt = new Chart(document.getElementById('doctorApptChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($doctorApptData['labels']) !!},
            datasets: [{
                label: 'Appointments',
                data: {!! json_encode($doctorApptData['values']) !!},
                backgroundColor: [navy, blue, teal, purple, orange],
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { x: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });

    // 9. Pre-existing Conditions Prevalence
    const chartConditions = new Chart(document.getElementById('conditionsChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($conditionsData['labels']) !!},
            datasets: [{
                label: 'Patients',
                data: {!! json_encode($conditionsData['values']) !!},
                backgroundColor: [red, orange, yellow, purple, teal],
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });

    // 10. Busiest Days of the Week
    const chartBusiestDays = new Chart(document.getElementById('busiestDaysChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($busiestDaysData['labels']) !!},
            datasets: [{
                label: 'Appointments',
                data: {!! json_encode($busiestDaysData['values']) !!},
                backgroundColor: navy,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });
</script>
@endsection
