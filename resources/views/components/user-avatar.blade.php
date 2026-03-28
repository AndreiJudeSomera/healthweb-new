@props(['role' => null, 'gender' => null, 'size' => 'w-9 h-9', 'iconSize' => 'text-base'])

@php
  $roleInt = (int) $role;
  if ($roleInt === 2) {
    $bg    = 'bg-indigo-100';
    $color = 'text-indigo-700';
    $icon  = 'fa-solid fa-user-doctor';
  } elseif ($roleInt === 1) {
    $bg    = 'bg-amber-100';
    $color = 'text-amber-700';
    $icon  = 'fa-solid fa-user-nurse';
  } else {
    // Patient — gender-based
    if (strtolower($gender ?? '') === 'female') {
      $bg    = 'bg-pink-100';
      $color = 'text-pink-700';
      $icon  = 'fa-solid fa-person-dress';
    } else {
      $bg    = 'bg-blue-100';
      $color = 'text-blue-700';
      $icon  = 'fa-solid fa-person';
    }
  }
@endphp

<div class="{{ $size }} rounded-full {{ $bg }} flex items-center justify-center flex-shrink-0">
  <i class="{{ $icon }} {{ $color }} {{ $iconSize }}"></i>
</div>
