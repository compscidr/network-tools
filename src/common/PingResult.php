<?php
class PingResult {
  public string $ip;
  public string $hostname;
  public float $avg_rtt_ms;
  public bool $down;
  public string $rawResult;
  public bool $valid;

  public function __construct(string $ip, string $hostname, float $avg_rtt_ms, bool $down, string $rawResult, bool $valid) {
    $this->ip = $ip;
    $this->hostname = $hostname;
    $this->avg_rtt_ms = $avg_rtt_ms;
    $this->down = $down;
    $this->rawResult = $rawResult;
    $this->valid = $valid;
  }
}
?>
