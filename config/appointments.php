<?php

return [
  'slot_capacity' => 1, // change to 3 later

  // fixed times (24h)
  'allowed_times' => [
    
    '08:00:00',
    '09:00:00',
    '10:00:00',
    '11:00:00',
    '13:00:00',
    '14:00:00',
    '15:00:00',
    '16:00:00',
  ],

  // statuses that count as occupying a slot
  'count_statuses' => ['pending', 'approved', 'completed'],
];
