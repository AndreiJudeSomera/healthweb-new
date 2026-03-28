@extends('layouts.app')

@section('title', 'Help & Support')

@section('content')
<main class="p-6 bg-gray-50 min-h-screen">
  <div class="max-w-3xl mx-auto">

    <div class="mb-5 flex items-center gap-3">
      <a href="{{ route('settings.setting') }}" class="text-gray-500 hover:text-blue-700 transition">
        <i class="fa-solid fa-arrow-left"></i>
      </a>
      <h1 class="text-xl font-semibold text-[#1F2B5B]">Help & Support</h1>
    </div>

    <div class="bg-white shadow-md rounded-2xl p-6 space-y-8">

      <div>
        <p class="text-sm text-gray-500">
          HealthWeb is a clinic management system designed to streamline patient care, scheduling, and records
          management. Below is a guide to the features available to you.
        </p>
      </div>

      {{-- Dashboard --}}
      <div>
        <div class="flex items-center gap-3 mb-3">
          <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-grip text-blue-800 text-sm"></i>
          </div>
          <h2 class="text-base font-semibold text-[#1F2B5B]">Dashboard</h2>
        </div>
        <ul class="text-sm text-gray-600 space-y-1.5 list-disc list-inside ml-2">
          <li>View a summary of total patients, consultations, records, and daily appointments.</li>
          <li>Manage the weekly <strong>Clinic Hours</strong> schedule — toggle open/closed status and set opening and closing times per day. Changes are reflected immediately on the patient dashboard.</li>
        </ul>
      </div>

      {{-- Patients --}}
      <div>
        <div class="flex items-center gap-3 mb-3">
          <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-hospital-user text-blue-800 text-sm"></i>
          </div>
          <h2 class="text-base font-semibold text-[#1F2B5B]">Patients</h2>
        </div>
        <ul class="text-sm text-gray-600 space-y-1.5 list-disc list-inside ml-2">
          <li>View the full list of registered patients and their status (new/old).</li>
          <li>Search and filter patients by name, PID, or record details.</li>
          <li>Access individual patient records including medical history and diagnoses.</li>
        </ul>
      </div>

      {{-- Appointments --}}
      <div>
        <div class="flex items-center gap-3 mb-3">
          <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-user-clock text-blue-800 text-sm"></i>
          </div>
          <h2 class="text-base font-semibold text-[#1F2B5B]">Appointments</h2>
        </div>
        <ul class="text-sm text-gray-600 space-y-1.5 list-disc list-inside ml-2">
          <li>View all scheduled, pending, and completed appointments.</li>
          <li>Approve, complete, or cancel appointment requests.</li>
          <li>Access the appointment queue to manage daily patient flow.</li>
        </ul>
      </div>

      {{-- Consultations --}}
      <div>
        <div class="flex items-center gap-3 mb-3">
          <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-stethoscope text-blue-800 text-sm"></i>
          </div>
          <h2 class="text-base font-semibold text-[#1F2B5B]">Consultations</h2>
        </div>
        <ul class="text-sm text-gray-600 space-y-1.5 list-disc list-inside ml-2">
          <li>Record patient vitals, diagnosis, and treatment notes per consultation.</li>
          <li>Issue prescriptions, medical certificates, and referral documents.</li>
          <li>Generate and download PDF documents for patient records.</li>
        </ul>
      </div>

      {{-- Messages --}}
      <div>
        <div class="flex items-center gap-3 mb-3">
          <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-paper-plane text-blue-800 text-sm"></i>
          </div>
          <h2 class="text-base font-semibold text-[#1F2B5B]">Messages</h2>
        </div>
        <ul class="text-sm text-gray-600 space-y-1.5 list-disc list-inside ml-2">
          <li>Send and receive messages with patients and colleagues.</li>
          <li>Search for users by name or email to start a new conversation.</li>
          <li>View message history and delete conversations as needed.</li>
        </ul>
      </div>

      {{-- Settings --}}
      <div>
        <div class="flex items-center gap-3 mb-3">
          <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-gear text-blue-800 text-sm"></i>
          </div>
          <h2 class="text-base font-semibold text-[#1F2B5B]">Settings</h2>
        </div>
        <ul class="text-sm text-gray-600 space-y-1.5 list-disc list-inside ml-2">
          <li><strong>Change Password</strong> — Update your account password. You will need to enter your current password first.</li>
          <li><strong>Update Profile</strong> — Change your username and email address.</li>
        </ul>
      </div>

      <p class="text-xs text-gray-400 border-t pt-4">
        For technical issues or account problems, contact your system administrator.
      </p>

    </div>
  </div>
</main>
@endsection
