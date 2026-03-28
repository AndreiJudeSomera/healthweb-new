@extends("pdfs.layout-pres")

@section("styles")
  <style>
    .patient-block {
      margin-top: 0;
    }

    .patient-left {
      width: 60%;
      float: left;
    }

    .patient-right {
      width: 38%;
      float: right;
      text-align: right;
    }

    .patient-line {
      margin: 4px 0;
    }

    .rx {
      margin:12px 5px;
      font-size: 35px;
      font-weight: 700;
      line-height: 1;
    }

    .rx small {
      font-size: 20px;
    }

    .drug {
      margin-top: 2px;
    }

    .drug-name {
      margin-top: 10px;
      font-weight: 700;
      font-size: 7px;
      letter-spacing: 1px;
    }

    .drug-meta {
      margin-top: 2px;
      line-height: 1.2;
      font-size: 7px;
      white-space: pre-wrap;
    }

    .instruction {
      margin-top: 1px;
    }

 
  </style>
@endsection

@section("content")
  @php
    $p = $consultation->patient;
    $appt = $consultation->appointment;

    $fullName = trim(
        collect([$p->first_name ?? null, $p->middle_name ?? null, $p->last_name ?? null])
            ->filter()
            ->implode(" "),
    );

    $age = $p->age ?? null;
    if (!$age && !empty($p->date_of_birth)) {
        $age = \Carbon\Carbon::parse($p->date_of_birth)->age;
    }

    $sex = !empty($p->gender) ? ucfirst($p->gender) : null;
    $address = $p->address ?? null;

    $dateValue = $appt?->appointment_date ?: $consultation->created_at;
    $dateLabel = \Carbon\Carbon::parse($dateValue)->format("M d, Y");

    // ✅ New normalized prescriptions (preferred)
    $prescriptions = $consultation->prescriptions ?? collect();

    // Legacy plain-text field fallback
    $medsText = trim((string) ($consultation->prescription_meds ?? ""));
  @endphp

  {{-- PATIENT INFO + DATE --}}
  <div class="patient-block clearfix">
    <div class="patient-left">  
    <div class="patient-line" style="font-size:7px;"><span class="muted">Name :</span> {{ $fullName ?: "—" }}</div>
    <div class="patient-line" style="font-size:7px;"><span class="muted">Age :</span> {{ $age ? $age . " yo" : "—" }}</div>
    <div class="patient-line=" style="font-size:7px;"><span class="muted">Sex :</span> {{ $sex ?: "—" }}</div>
      <div class="patient-line=" style="font-size:7px;"><span class="muted">Address :</span> {{ $address ?: "—" }}</div>
    </div>

    <div class="patient-right">
      <div style="font-size:8px; font-weight:500;"> 
        <span class="muted">Date :</span> {{ $dateLabel }}
      </div>
    </div>
  </div>

  {{-- RX --}}
  <div class="rx">R<small>x</small></div>

  {{-- MEDS (normalized first) --}}
  @if ($prescriptions instanceof \Illuminate\Support\Collection ? $prescriptions->isNotEmpty() : count($prescriptions))
    @foreach ($prescriptions as $rx)
      @php
        // Name from dropdown table (prescription_items)
        $rawName = $rx->medicine?->medicine_name ?? null;

        // Fallback if relation missing (shouldn’t happen if FK is valid)
        $name = strtoupper($rawName ?: "MEDICINE");

        // Build details lines (only include non-empty)
        $lines = collect([
            $rx->dosage ? "Dosage: {$rx->dosage}" : null,
            $rx->frequency ? "Frequency: {$rx->frequency}" : null,
            $rx->duration ? "Duration: {$rx->duration}" : null,
        ])
            ->filter()
            ->implode("\n");

        $instructions = trim((string) ($rx->instructions ?? ""));
      @endphp

      <div class="drug">
        <div class="drug-name">{{ $name }}</div>

        @if ($lines !== "")
          <div class="drug-meta">{{ $lines }}</div>
        @endif

        @if ($instructions !== "")
          <div class="instruction" style="font-size:7px;">
            <span class="muted">Instruction:</span> {{ $instructions }}
          </div>
        @endif
      </div>
      
      


      <div style="margin-top:18px;"></div>
    @endforeach

    {{-- FALLBACK: legacy plain text --}}
  @else
    <div class="drug">
      <div class="drug-name">PRESCRIPTION</div>
      <div class="drug-meta">{{ $medsText !== "" ? $medsText : "—" }}</div>
    </div>
  @endif

  {{-- SIGNATURE --}}
  @include("pdfs.partials.doctor-signature-pres", ["consultation" => $consultation])
@endsection
