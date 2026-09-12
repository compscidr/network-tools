<?php

function createTable() {
  $db = new PingDB();
  if (!$db) {
    echo $db->lastErrorMsg();
    return;
  }
  // hostname maximum length: https://web.archive.org/web/20190518124533/https://devblogs.microsoft.com/oldnewthing/?p=7873
  $sql =<<<EOF
      CREATE TABLE IF NOT EXISTS PING
      (ID INTEGER PRIMARY KEY AUTOINCREMENT,
      HOST           CHAR(253)  NOT NULL,
      IP             CHAR(15)   NOT NULL,
      DOWN           BOOLEAN    NOT NULL,
      AVG_RTT_MS     FLOAT      NOT NULL,
      TIMESTAMP DATETIME DEFAULT CURRENT_TIMESTAMP);
  EOF;
  $ret = $db->exec($sql);
   if(!$ret){
      echo $db->lastErrorMsg();
   } else {
      echo "Table created successfully\n";
   }
   $db->close();
}

createTable();
?>
