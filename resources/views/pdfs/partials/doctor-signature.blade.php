  @php
  $doctor = $consultation->doctor; 
  $clinicStaff = $doctor?->clinicStaff;
  $user = $clinicStaff?->user;

  // Get middle initial
  $middleInitial = $clinicStaff && $clinicStaff->Mname 
      ? strtoupper(substr($clinicStaff->Mname, 0, 1)) . '.'
      : '';

  $doctorName = $clinicStaff 
      ? "" . strtoupper($clinicStaff->Fname . ' ' . $middleInitial . ' ' . $clinicStaff->Lname) . ", MD"
      : "NAME";

  $licenseNo = $doctor?->dr_license_no ?? "______________________";
  $ptrNo = $doctor?->ptr_no ?? "__________________________";
  @endphp

  <div class="signature clearfix" style="margin-left:150px;">
    <div class="sig-right">
      <div class="sig-name">{{ $doctorName }}</div>
      <div style="margin-top:6px;">License No: {{ $licenseNo }}</div>
      <div style="margin-top:6px;">PTR No: {{ $ptrNo }}</div>
    </div>
  </div>

  