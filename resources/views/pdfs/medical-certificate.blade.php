@extends("pdfs.layout")

@section("styles")
  <style>
    .info-row {
      margin-top: 24px;
    }

    .info-left {
      width: 60%;
      float: left;
    }

    .info-right {
      width: 38%;
      float: right;
      text-align: right;
    }

    .line {
      margin: 4px 0;
    }

    .section-title {
      font-weight: 700;
      letter-spacing: 2px;
      margin-top: 24px;
    }

    .cert-body {
      margin-top: 40px;
      text-align: justify;
      line-height: 1.8;
      font-size: 14px;
    }

    .remarks {
      margin-top: 80px;
      font-size: 13px;
    }

    .footer-note {
      margin-top: 70px;
      text-align: center;
      font-size: 12px;
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

    // if you don't have stored age, compute it (optional)
    $age = $p->age ?? null;
    if (!$age && !empty($p->date_of_birth)) {
        $age = \Carbon\Carbon::parse($p->date_of_birth)->age;
    }

    $sex = !empty($p->gender) ? strtoupper($p->gender) : null;
    $address = $p->address ?? null;

    $dateValue = $appt?->appointment_date ?: $consultation->created_at;
    $dateLabel = \Carbon\Carbon::parse($dateValue)->format("M d, Y");

    $diagnosis = trim((string) ($consultation->diagnosis ?? ""));
    $remarks = trim((string) ($consultation->remarks ?? ""));

    $referralTo = trim((string) ($consultation->referral_to ?? ""));
    $referralReason = trim((string) ($consultation->referral_reason ?? ""));
  @endphp

  <div class="title">MEDICAL CERTIFICATE</div>

  <div class="row clearfix info-row">
    <div class="info-left">
      <div class="section-title">PATIENT INFORMATION</div>
      <div class="line"><span class="muted">Name :</span> {{ $fullName !== "" ? $fullName : "—" }}</div>
    </div>

    <div class="info-right">
      <div style="font-size:14px;">
        <span class="muted">Date :</span> {{ $dateLabel }}
      </div>
    </div>
  </div>

  <div class="cert-body">
    This is to certify that
    <strong>{{ $fullName !== "" ? strtoupper($fullName) : "________________" }}</strong>,
    {{ $age ? $age . "y.o" : "___y.o" }}, {{ $sex ?: "____" }},
    currently residing at <span>{{ $address ?: "________________" }}</span>
    has been seen and examined in my clinic on <span>{{ $dateLabel }}</span>
    and was given the following diagnosis:
    <span>{{ $diagnosis !== "" ? strtoupper($diagnosis) : "________________________" }}</span>
  </div>

  <div class="remarks">
    <strong>REMARKS:</strong> {{ $remarks !== "" ? $remarks : "—" }}
  </div>

  <div class="footer-note">
    This certificate is issued upon the request of {{ $referralTo ?: "________________" }} for
    {{ $referralReason ?: "________________" }}, except medico legal.
  </div>

  @include("pdfs.partials.doctor-signature", ["consultation" => $consultation])
@endsection
