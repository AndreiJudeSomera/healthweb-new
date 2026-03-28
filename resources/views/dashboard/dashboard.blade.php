@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<main class="flex-1 p-4">
    <!-- resources/views/dashboard.blade.php -->

<div class="grid grid-cols-2 gap-4 p-4">
  <!-- Age Demographics -->
  <div class="bg-white p-4 rounded shadow">
    <h2 class="font-bold mb-2">Patient Age Demographics</h2>
    <canvas id="ageChart"></canvas>
  </div>

  <!-- Gender Ratio -->
  <div class="bg-white p-4 rounded shadow">
    <h2 class="font-bold mb-2">Gender Ratio</h2>
    <canvas id="genderChart"></canvas>
  </div>

  <!-- Common Diseases -->
  <div class="bg-white p-4 rounded shadow">
    <h2 class="font-bold mb-2">Common Diseases</h2>
    <canvas id="diseaseChart"></canvas>
  </div>

  <!-- Monthly Patient Additions -->
  <div class="bg-white p-4 rounded shadow">
    <h2 class="font-bold mb-2">Monthly Patient Additions</h2>
    <canvas id="monthlyChart"></canvas>
  </div>
</div>

<div class="flex justify-center mt-4">
  <button class="bg-white border px-4 py-2 rounded-full hover:bg-gray-100">See all</button>
</div>

<script>
  // Age Demographics
  new Chart(document.getElementById('ageChart'), {
    type: 'bar',
    data: {
      labels: {!! json_encode($ageData['labels']) !!},
      datasets: [{
        data: {!! json_encode($ageData['values']) !!},
        backgroundColor: ['#90ee90', '#00bfff', '#ff4136', '#2ecc40', '#001f3f']
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: { beginAtZero: true }
      }
    }
  });

  // Gender Ratio
  new Chart(document.getElementById('genderChart'), {
    type: 'doughnut',
    data: {
      labels: {!! json_encode($genderData['labels']) !!},
      datasets: [{
        data: {!! json_encode($genderData['values']) !!},
        backgroundColor: ['#111c44', '#e039f8']
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          labels: {
            color: '#000',
            font: { size: 14, weight: 'bold' }
          }
        }
      }
    }
  });

  // Common Diseases
  new Chart(document.getElementById('diseaseChart'), {
    type: 'line',
    data: {
      labels: {!! json_encode($diseaseData['labels']) !!},
      datasets: [{
        data: {!! json_encode($diseaseData['values']) !!},
        borderColor: 'lime',
        backgroundColor: 'lime',
        tension: 0.3
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: { beginAtZero: true }
      }
    }
  });

  // Monthly Patient Additions
  new Chart(document.getElementById('monthlyChart'), {
    type: 'bar',
    data: {
      labels: ['Month'],
      datasets: [{
        data: {!! json_encode($monthlyData) !!},
        backgroundColor: '#111c44'
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: { beginAtZero: true }
      }
    }
  });
</script>


@endsection
