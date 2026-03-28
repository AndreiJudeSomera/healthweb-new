<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TwilioService {
  private string $accountSid;
  private string $authToken;
  private string $fromNumber;

  public function __construct() {
    $this->accountSid = config('services.twilio.account_sid', '');
    $this->authToken  = config('services.twilio.auth_token', '');
    $this->fromNumber = config('services.twilio.from_number', '');
  }

  public function send(string $number, string $message): void {
    if (empty($this->accountSid) || empty($this->authToken) || empty($this->fromNumber)) {
      Log::warning('TwilioService: TWILIO_ACCOUNT_SID, TWILIO_AUTH_TOKEN, or TWILIO_FROM_NUMBER is not set.');
      return;
    }

    // Twilio requires E.164 format (+country code). Normalise PH numbers if needed.
    $to = $this->toE164($number);

    try {
      $response = Http::withBasicAuth($this->accountSid, $this->authToken)
        ->asForm()
        ->post("https://api.twilio.com/2010-04-01/Accounts/{$this->accountSid}/Messages.json", [
          'From' => $this->fromNumber,
          'To'   => $to,
          'Body' => $message,
        ]);

      if ($response->failed()) {
        Log::error('TwilioService: API returned an error.', [
          'to'     => $to,
          'status' => $response->status(),
          'body'   => $response->body(),
        ]);
      }
    } catch (\Throwable $e) {
      Log::error('TwilioService: Failed to send SMS.', [
        'to'    => $to,
        'error' => $e->getMessage(),
      ]);
    }
  }

  /**
   * Normalise a local PH number (09xxxxxxxxx) to E.164 (+639xxxxxxxxx).
   * Numbers that already start with '+' are returned as-is.
   */
  private function toE164(string $number): string {
    $digits = preg_replace('/\D/', '', $number);

    if (str_starts_with($number, '+')) {
      return '+' . $digits;
    }

    // 09xxxxxxxxx → +639xxxxxxxxx
    if (str_starts_with($digits, '0')) {
      $digits = '63' . substr($digits, 1);
    }

    return '+' . $digits;
  }
}
