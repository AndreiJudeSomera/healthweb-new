<?php

namespace App\Services;

class SmsService {
  public function send(string $phoneNumber, string $message): bool {
    // later: Twilio / Semaphore / Vonage
    // For now: no-op
    return true;
  }
}
