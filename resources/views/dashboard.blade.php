@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-4">

<!-- Total Patients -->
<a href="{{ route('patients.index') }}"
   class="bg-blue-950 border border-blue-950 rounded-md shadow-sm p-5 flex flex-col gap-3 hover:bg-blue-900 hover:shadow-md transition-all group">

    <div class="flex items-center justify-between">
        <span class="text-xs font-medium text-blue-100 uppercase tracking-wide">
            Total Patients
        </span>

        <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center group-hover:bg-white/20 transition-colors">
            <i class="fa-solid fa-hospital-user text-white"></i>
        </div>
    </div>

    <p class="text-3xl font-bold text-white">{{ $totalPatients }}</p>

    <span class="text-xs text-blue-200 font-medium">
        View all patients →
    </span>

</a>


<!-- Daily Average Consultation -->

<!-- Total Records -->
<a href="{{ route('patients.index') }}"
   class="bg-blue-950 border border-blue-950 rounded-md shadow-sm p-5 flex flex-col gap-3 hover:bg-blue-900 hover:shadow-md transition-all group">

    <div class="flex items-center justify-between">
        <span class="text-xs font-medium text-blue-100 uppercase tracking-wide">
            Total Records
        </span>

        <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center group-hover:bg-white/20 transition-colors">
            <i class="fa-solid fa-file-medical text-white"></i>
        </div>
    </div>

    <p class="text-3xl font-bold text-white">{{ $totalRecords }}</p>

    <span class="text-xs text-blue-200 font-medium">
        View all records →
    </span>

</a>


<a href="{{ route('appointments.queue') }}"
   class="bg-blue-950 border border-blue-950 rounded-md shadow-sm p-5 flex flex-col gap-3 hover:bg-blue-900 hover:shadow-md transition-all group">

    <div class="flex items-center justify-between">
        <span class="text-xs font-medium text-blue-100 uppercase tracking-wide">
            TODAY'S CONSULTATIONS
        </span>

        <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center group-hover:bg-white/20 transition-colors">
            <i class="fa-solid fa-stethoscope text-white"></i>
        </div>
    </div>

    <p class="text-3xl font-bold text-white">{{ $totalConsultationsToday }}</p>

    <span class="text-xs text-blue-200 font-medium">
        View today's queue →
    </span>

</a>


<!-- Daily Total Appointments -->
<a href="{{ route('appointments.index') }}"
   class="bg-blue-950 border border-blue-950 rounded-md shadow-sm p-5 flex flex-col gap-3 hover:bg-blue-900 hover:shadow-md transition-all group">

    <div class="flex items-center justify-between">
        <span class="text-xs font-medium text-blue-100 uppercase tracking-wide">
            Today's Appointments
        </span>

        <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center group-hover:bg-white/20 transition-colors">
            <i class="fa-solid fa-user-clock text-white"></i>
        </div>
    </div>

    <p class="text-3xl font-bold text-white">{{ $todayAppointments }}</p>

    <span class="text-xs text-blue-200 font-medium">
        View appointments →
    </span>

</a>


</div>

{{-- Clinic Hours Management --}}

<div x-data="clinicHoursManager()" x-init="init()">


<div class="bg-white rounded-md shadow-sm border border-gray-200 overflow-hidden">

    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <i class="fa-regular fa-clock text-blue-950 text-lg"></i>
            <h2 class="text-lg font-semibold text-blue-950">Clinic Hours</h2>
        </div>

        <span class="text-xs text-gray-400">
            Visible to patients on their dashboard
        </span>
    </div>


    <template x-if="loading">
        <div class="p-6 text-sm text-gray-400">
            Loading schedule...
        </div>
    </template>


    <template x-if="!loading">

        <div class="divide-y divide-gray-100">

            <template x-for="day in schedule" :key="day.id">

                <div class="flex items-center justify-between px-4 sm:px-6 py-3">

                    <span class="w-28 text-sm font-medium text-gray-700 flex-shrink-0"
                          x-text="day.day_name"></span>


                    <div class="flex items-center gap-3 flex-1 justify-end">

                        <label class="relative inline-flex items-center cursor-pointer">

                            <input type="checkbox"
                                   class="sr-only peer"
                                   :checked="day.is_open"
                                   @change="toggleOpen(day)">

                            <div class="w-10 h-5 bg-gray-300 rounded-full peer peer-checked:bg-blue-950
                                after:content-[''] after:absolute after:top-0.5 after:left-0.5
                                after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all
                                peer-checked:after:translate-x-5">
                            </div>

                        </label>


                        <template x-if="day.is_open">

                            <div class="flex items-center gap-2">

                                <input type="time"
                                       class="border border-gray-300 rounded-md px-2 py-1 text-sm w-32
                                       focus:outline-none focus:ring-1 focus:ring-blue-400"
                                       :value="stripSeconds(day.open_time)"
                                       @change="day.open_time = $event.target.value">

                                <span class="text-gray-400 text-sm">to</span>

                                <input type="time"
                                       class="border border-gray-300 rounded-md px-2 py-1 text-sm w-32
                                       focus:outline-none focus:ring-1 focus:ring-blue-400"
                                       :value="stripSeconds(day.close_time)"
                                       @change="day.close_time = $event.target.value">

                                <button
                                    @click="saveDay(day)"
                                    :disabled="day.saving"
                                    class="px-3 py-1 text-sm bg-blue-950 text-white rounded-md
                                    hover:bg-blue-900 disabled:opacity-50 transition-colors">

                                    <span x-text="day.saving ? 'Saving...' : 'Save'"></span>

                                </button>

                            </div>

                        </template>


                        <template x-if="!day.is_open">

                            <span class="text-sm text-red-500 font-medium">
                                No Clinic
                            </span>

                        </template>

                    </div>

                </div>

            </template>

        </div>

    </template>

</div>



</div>


<script>
  function clinicHoursManager() {
    return {
      schedule: [],
      loading: true,

      async init() {
        try {
          const res = await fetch('/api/clinic-schedule');
          const json = await res.json();
          this.schedule = json.data.map(d => ({ ...d, saving: false }));
        } catch (e) {
          console.error('Failed to load clinic schedule', e);
        } finally {
          this.loading = false;
        }
      },

      stripSeconds(time) {
        if (!time) return '';
        return time.substring(0, 5);
      },

      async toggleOpen(day) {
        day.is_open = !day.is_open;
        await this.saveDay(day);
      },

      async saveDay(day) {
        day.saving = true;
        try {
          const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
          const res = await fetch(`/api/clinic-schedule/${day.id}`, {
            method: 'PUT',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': token,
            },
            body: JSON.stringify({
              is_open: day.is_open,
              open_time: day.is_open ? this.stripSeconds(day.open_time) : null,
              close_time: day.is_open ? this.stripSeconds(day.close_time) : null,
            }),
          });
          const json = await res.json();
          if (res.ok) {
            Object.assign(day, json.data);
            toastr?.success?.('Clinic hours updated.');
          } else {
            toastr?.error?.(json.message || 'Failed to update.');
          }
        } catch (e) {
          console.error('Error saving clinic schedule', e);
          toastr?.error?.('Failed to update clinic hours.');
        } finally {
          day.saving = false;
        }
      },
    };
  }
</script>
@endsection
