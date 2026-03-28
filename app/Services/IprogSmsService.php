<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IprogSmsService {
  private string $apiToken;

  public function __construct() {
    $this->apiToken = config('services.iprogsms.api_token', '');
  }

  public function send(string $number, string $message): void {
    if (empty($this->apiToken)) {
      Log::warning('IprogSmsService: IPROGSMS_API_TOKEN is not set.');
      return;
    }

    $phone = $this->normalise($number);

    try {
      $response = Http::asJson()->post('https://www.iprogsms.com/api/v1/sms_messages', [
        'api_token'    => $this->apiToken,
        'phone_number' => $phone,
        'message'      => $message,
      ]);

      if ($response->failed()) {
        Log::error('IprogSmsService: API returned an error.', [
          'to'     => $phone,
          'status' => $response->status(),
          'body'   => $response->body(),
        ]);
      }
    } catch (\Throwable $e) {
      Log::error('IprogSmsService: Failed to send SMS.', [
        'to'    => $phone,
        'error' => $e->getMessage(),
      ]);
    }
  }

  /**
   * Normalise PH numbers to 639xxxxxxxxx (no leading +).
   * Semaphore and iprogsms use the local format without a + prefix.
   */
  private function normalise(string $number): string {
    $digits = preg_replace('/\D/', '', $number);

    // 09xxxxxxxxx → 639xxxxxxxxx
    if (str_starts_with($digits, '0')) {
      $digits = '63' . substr($digits, 1);
    }

    return $digits;
  }
}
