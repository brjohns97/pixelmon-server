<?php
include '../../navbar.php';
if (!isset($logfile)) {
    $logfile = '/pixelmon/logs/latest.log';
}
if (!isset($self)) {
    $self = '/logs/latest/';
}
if (!isset($name)) {
    $name = 'Latest';
}

$lines = @file($logfile);
$tail = 250;

if (isset($_GET['tail']) && is_numeric($_GET['tail'])) {
   $tail = (int)$_GET['tail'];
}

if (isset($_GET['full'])) {
   $tail = false;
}

if ($tail !== false) {
   $lines = array_splice($lines, -$tail);
}

echo '<title>'.$name.' Log</title>';
echo '<style>body {background: black;</style>';
echo '<body style="font-family: monospace">';

$size = (int)(filesize($logfile) / 1024) . ' KB';
echo '<p style="color: white; padding: 1em; border: 1px solid green;">';
if ($tail !== false) {
   echo 'The log has been clipped to ' . $tail . ' entries. To see the full log, ';
   echo 'click <a style="color:Yellow"href="'.$self.'?full">here</a>. ';
}
echo 'WARNING: The full log may be slow to load. Log file size is ' . $size . '.</p>';

$start_time = microtime(true);
if ($lines === false) {
   echo '<p style="color: white; padding: 1em; border: 3px solid green;">No log file.</p>';
   $lines = [];
}
foreach ($lines as $line) {
   # Parse some basic ANSI codes
   echo '<span style="color: white">';
   $last_color = null;
   $prev = '';
   $color = '';
   for ($i = 0; $i < strlen($line); $i++) {
      if ($line[$i] == chr(0x1b)) {
         # ANSI code; some codes include a brightness qualifier, but all color codes
         # end with an ASCII 'm'.
         $end_of_code = strpos($line, 'm', $i + 1);
         $color_code = substr($line, $i + 1, $end_of_code - $i);
         if (array_key_exists($color_code, $colors)) {
            $color = $colors[$color_code];
            if ($last_color != $color) {
               echo "</span><span style=\"color: $color\">";
            }
            $i = $end_of_code;
            $last_color = $color;
         }
      }
      else {
         # Not ansi code
         if ($line[$i] == '<') {
            echo '&lt;';
         }
         else if ($line[$i] == '>') {
            echo '&gt;';
         }
         else {
            echo ($prev == ' ' && $line[$i] == ' ') ? '&nbsp;' : $line[$i];
            $prev = $line[$i];
         }
      }
   }
   echo '</span><br />';
}
$total = microtime(true) - $start_time;
if ($tail === false) {
   $dur = number_format($total, 2, '.', '') . ' seconds';
   echo '<p style="color: white; padding: 1em; border: 1px solid green;">This colorful log took ' . $dur . ' to generate.</p>';
}
echo '</body>';

?>
