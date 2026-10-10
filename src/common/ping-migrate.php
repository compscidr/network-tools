<?php
// PingDB creates the table on open; this just forces it for a fresh db.
(new PingDB())->close();
echo "Table created successfully\n";
?>
