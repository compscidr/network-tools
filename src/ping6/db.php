<?php

// https://www.tutorialspoint.com/sqlite/sqlite_php.htm
class PingDB extends SQLite3 {
  function __construct() {
    $this->open("/data/ping6.db");
  }

  function addPingResult(PingResult $result) {
    $sql =<<<EOF
      INSERT INTO PING (HOST,IP,DOWN,AVG_RTT_MS)
      VALUES ('$result->hostname', '$result->ip', '$result->down', $result->avg_rtt_ms);
    EOF;
    $ret = $this->exec($sql);
    if(!$ret) {
      echo $this->lastErrorMsg();
    }
    $this->close();
  }

  /**
   * Returns an associative array of timestamp -> avg_rtt_ms over the last 24
   * hours for the particular ping result (by IP address)
   */
  function last24HoursPingResults($pingResult): array {
    $orderedResult = array();
    $sql =<<<EOF
      SELECT TIMESTAMP,AVG_RTT_MS FROM PING WHERE TIMESTAMP >= DATE('now', '-1 days') AND (HOST='$pingResult->hostname' OR IP='$pingResult->ip') AND AVG_RTT_MS!=0
    EOF;
    $results = $this->query($sql);
    while ($row = $results->fetchArray()) {
      //var_dump($row);
      $orderedResult[$row[0]] = $row[1];
    }
    return $orderedResult;
  }

  function statComparison($pingResult): array {
    $orderedResult = array();
    $sql =<<<EOF
      SELECT AVG(NULLIF(AVG_RTT_MS, 0)) FROM PING WHERE (HOST='$pingResult->hostname' OR IP='$pingResult->ip')
    EOF;
    $results = $this->query($sql);
    $row = $results->fetchArray();
    if($row[0] == 0) {
      $row[0] = 0.0;
    }
    $orderedResult["Historical Average"] = number_format($row[0], 2, '.', '')."ms";

    $sql =<<<EOF
      SELECT COUNT(ID) FROM PING WHERE (HOST='$pingResult->hostname' OR IP='$pingResult->ip')
    EOF;
    $results = $this->query($sql);
    $row = $results->fetchArray();
    $orderedResult["Total Pings"] = $row[0];

    return $orderedResult;
  }

  function globalStats(): array {
    $orderedResult = array();

    $sql =<<<EOF
      SELECT COUNT(ID) FROM PING
    EOF;
    $results = $this->query($sql);
    $row = $results->fetchArray();
    $orderedResult["Total Pings"] = $row[0];

    $sql =<<<EOF
      SELECT AVG(NULLIF(AVG_RTT_MS, 0)) FROM PING
    EOF;
    $results = $this->query($sql);
    $row = $results->fetchArray();
    if($row[0] == 0) {
      $row[0] = 0.0;
    }
    $orderedResult["Average RTT"] = number_format($row[0], 2, '.', '')."ms";

    return $orderedResult;
  }

  function lastPings($n): array {
    $result = array();
    $sql =<<<EOF
      SELECT DISTINCT HOST FROM PING ORDER BY TIMESTAMP DESC LIMIT $n
    EOF;
    $results = $this->query($sql);
    while ($row = $results->fetchArray()) {
      array_push($result, $row[0]);
    }
    return $result;
  }
}

?>
