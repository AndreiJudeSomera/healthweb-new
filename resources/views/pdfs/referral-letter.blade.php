@extends("pdfs.layout")

@section("styles")
  <style>
    .title {
      text-align: center;
      font-weight: 700;
      letter-spacing: 2px;
      margin: 18px 0 34px;
      font-size: 16px;
    }

    .date-right {
      text-align: right;
      margin-top: 4px;
      font-size: 14px;
    }

    .to {
      margin-top: 40px;
      font-weight: 500;
      letter-spacing: 1px;
    }

    .body {
      margin-top: 32px;
      line-height: 1.6;
    }

    .patient-table {
      margin-top: 14px;
    }

    .pt-row {
      margin: 4px 0;
    }

    .pt-label {
      display: inline-block;
      width: 60px;
    }

    .pt-val {
      font-weight: 700;
    }

    .section {
      margin-top: 55px;
    }

    .section-title {
      font-weight: 700;
      letter-spacing: 1px;
      margin-bottom: 10px;
    }

    /* signature spacing (layout already defines signature styles, this just matches your old spacing) */
    .signature {
      margin-top: 120px;
    }
  </style>
@endsection

@section("content")
  @php
    // single source of truth
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

    $sex = !empty($p->gender) ? strtoupper($p->gender) : null;

    $dateValue = $appt?->appointment_date ?: $consultation->created_at;
    $dateLabel = \Carbon\Carbon::parse($dateValue)->format("M d, Y");

    $toDoctor = trim((string) ($consultation->referral_to ?? ""));
    $diagnosis = trim((string) ($consultation->diagnosis ?? ""));
    $reason = trim((string) ($consultation->referral_reason ?? ""));
  @endphp

  <div class="title">REFERRAL LETTER</div>

  <div class="date-right">
    <span class="muted">Date :</span> {{ $dateLabel }}
  </div>

  <div class="to">
    TO : {{ strtoupper($toDoctor !== "" ? $toDoctor : "DR. ________") }}
  </div>

  <div class="body">
    <div>Good Day !</div>
    <div>Respectfully referring patient:</div>

    <div class="patient-table">
      <div class="pt-row">
        <span class="pt-label">Name :</span>
        <span class="pt-val">{{ $fullName !== "" ? strtoupper($fullName) : "—" }}</span>
      </div>

      <div class="pt-row">
        <span class="pt-label">Age :</span>
        <span class="pt-val">{{ $age ? $age . " Y.O" : "—" }}</span>
      </div>

      <div class="pt-row">
        <span class="pt-label">Sex :</span>
        <span class="pt-val">{{ $sex ?: "—" }}</span>
      </div>
    </div>
  </div>

  <div class="section">
    <div class="section-title">DIAGNOSIS</div>
    <div>{{ $diagnosis !== "" ? $diagnosis : "—" }}</div>
  </div>

  <div class="section" style="margin-top:45px;">
    <div class="section-title">REASON FOR REQUEST</div>
    <div>{{ $reason !== "" ? $reason : "—" }}</div>
  </div>

  <div style="margin-top:70px;">Thank You Very Much !</div>

  @include("pdfs.partials.doctor-signature", ["consultation" => $consultation])
@endsection
