@extends("pdfs.layout")

@section("styles")
  <style>
    /* Page typography */
    .doc {
      font-size: 14px;
      color: #111;
    }

    /* Header */
    .header {
      padding-top: 6px;
    }

    .header-left {
      float: left;
      width: 48%;
    }

    .header-right {
      float: right;
      width: 50%;
      text-align: right;
      font-size: 11px;
      line-height: 1.35;
    }

    .logo {
      width: 180px;
      height: auto;
    }

    .divider {
      margin: 14px 0 22px;
      border-top: 2px solid #111;
    }

    .title {
      text-align: center;
      font-weight: 700;
      letter-spacing: 2px;
      font-size: 16px;
      margin: 4px 0 18px;
    }

    /* Sections */
    .section {
      margin-top: 18px;
    }

    .section h3 {
      font-size: 12px;
      font-weight: 700;
      letter-spacing: 1px;
      margin: 0 0 8px;
      text-transform: uppercase;
    }

    /* Patient info (two columns like sample) */
    .patient-info {
      /* margin-top: 10px; */
    }

    .patient-left {
      float: left;
      width: 62%;
    }

    .patient-right {
      float: right;
      width: 35%;
      text-align: right;
    }

    .line {
      margin: 4px 0;
      line-height: 1.4;
    }

    .muted {
      color: #333;
      font-weight: 600;
    }

    /* Vitals row */
    .vitals-row {
      margin-top: 6px;
      padding-top: 4px;
      font-size: 12px;
      letter-spacing: .5px;
      word-spacing: 2px;
      display: grid;
      grid-template-columns: repeat(6, 1fr);
      width: 100%;
      gap: 8px;
      width: 100%;
    }

    .vital {
      display: inline-block;
      margin-right: 18px;
      white-space: nowrap;
    }

    .vital b {
      font-weight: 700;
    }

    /* Paragraph body */
    .body-text {
      white-space: pre-wrap;
      /* keep newlines from textarea */
      line-height: 1.55;
      margin-top: 2px;
    }

    /* Signature spacing */
    .signature {
      margin-top: 90px;
    }

    /* Clearfix */
    .clearfix:after {
      content: "";
      display: table;
      clear: both;
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

    // Date: prefer consultation_date, then appointment_date, then created_at
    $dateValue = $consultation->consultation_date ?? ($appt?->appointment_date ?? $consultation->created_at);

    $dateLabel = $dateValue ? \Carbon\Carbon::parse($dateValue)->format("M d, Y") : "—";

    // Vitals (adjust field names if your DB uses different columns)
    $wt = $consultation->wt ?? null;
    $bp = $consultation->bp ?? null;
    $cr = $consultation->cr ?? null;
    $rr = $consultation->rr ?? null;
    $temp = $consultation->temperature ?? null;
    $spo2 = $consultation->sp02 ?? ($consultation->spo2 ?? null);

    // Notes
    $hpe = trim((string) ($consultation->history_physical_exam ?? ""));
    $dx = trim((string) ($consultation->diagnosis ?? ""));
    $tx = trim((string) ($consultation->treatment ?? ""));
  @endphp

  <div class="doc">
    <div class="title">CONSULTATION RECORD</div>

    {{-- PATIENT INFORMATION --}}
    <div class="section">
      <h3>Patient Information</h3>

      <div class="patient-info clearfix">
        <div class="patient-left">
          <div class="line"><span class="muted">Date :</span> {{ $dateLabel }}</div>
          <div class="line"><span class="muted">Name :</span> {{ $fullName ?: "—" }}</div>
          <div class="line"><span class="muted">Age :</span> {{ $age ? $age . " yo" : "—" }}</div>
          <div class="line"><span class="muted">Sex :</span> {{ $sex ?: "—" }}</div>
          <div class="line"><span class="muted">Address :</span> {{ $address ?: "—" }}</div>
        </div>
      </div>
    </div>

    {{-- VITAL SIGNS --}}
    <div class="section">
      <h3>Vital Signs</h3>

      <div class="vitals-row">
        <span class="vital"><b>WT</b> {{ $wt !== null && $wt !== "" ? $wt . " kg" : "—" }}</span>
        <span class="vital"><b>BP</b> {{ $bp ?: "—" }}</span>
        <span class="vital"><b>CR</b> {{ $cr !== null && $cr !== "" ? $cr . " BPM" : "—" }}</span>
        <span class="vital"><b>RR</b> {{ $rr !== null && $rr !== "" ? $rr . " CPM" : "—" }}</span>
        <span class="vital"><b>T</b> {{ $temp !== null && $temp !== "" ? $temp . " °C" : "—" }}</span>
        <span class="vital"><b>SPO2</b> {{ $spo2 !== null && $spo2 !== "" ? $spo2 . "%" : "—" }}</span>
      </div>
    </div>

    {{-- HISTORY / PHYSICAL EXAM --}}
    <div class="section">
      <h3>History/Physical Exam</h3>
      <div class="body-text">{{ $hpe !== "" ? $hpe : "—" }}</div>
    </div>

    {{-- DIAGNOSIS --}}
    <div class="section">
      <h3>Diagnosis</h3>
      <div class="body-text">{{ $dx !== "" ? $dx : "—" }}</div>
    </div>

    {{-- TREATMENT --}}
    <div class="section">
      <h3>Treatment</h3>
      <div class="body-text">{{ $tx !== "" ? $tx : "—" }}</div>
    </div>
  </div>
  @include("pdfs.partials.doctor-signature", ["consultation" => $consultation])
@endsection
