<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SemaphoreService {
  private string $apiKey;
  private string $senderName;

  public function __construct() {
    $this->apiKey     = config('services.semaphore.api_key', '');
    $this->senderName = config('services.semaphore.sender_name', 'SEMAPHORE');
  }

  public function send(string $number, string $message): void {
    if (empty($this->apiKey)) {
      Log::warning('SemaphoreService: SEMAPHORE_API_KEY is not set.');
      return;
    }

    try {
      Http::post('https://api.semaphore.co/api/v4/messages', [
        'apikey'      => $this->apiKey,
        'number'      => $number,
        'message'     => $message,
        'sendername'  => $this->senderName,
      ]);
    } catch (\Throwable $e) {
      Log::error('SemaphoreService: Failed to send SMS.', [
        'number'  => $number,
        'error'   => $e->getMessage(),
      ]);
    }
  }
}
