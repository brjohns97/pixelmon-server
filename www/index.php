<?php

$usercache_file_string = file_get_contents("/pixelmon/usercache.json");
$user_cache = json_decode(file_get_contents("/pixelmon/usercache.json"), true);
exec("ps aux | grep -i 'pixelmon_server' | grep -v grep", $pids);
if(empty($pids)) {
  $server_is_up = 0;
} else {
  $server_is_up = 1;
}
?>

<!DOCTYPE html>
<html lang="en">
<title>Brads Pixelmon Server</title>

<?php
include 'navbar.php'
?>

<body>
  <div class="container mt-3">
    <h2>Is the Server Up?</h2>
    <?php
      if ($server_is_up) {
        echo '<img src="images/yarp-hot.gif"/>';
      } else {
        echo '<img src="images/pokemon-no.gif"/>';
      }
    ?>
  </div>
  <div class="container mt-3">
    <h2>Who has been on the Pixelmon Server?</h2>
    <table class="table">
      <thead>
        <tr>
          <th>Username</th>
          <th>UUID</th>
          <th>Expires On</th>
        </tr>
      </thead>
      <tbody>
        <?php
          foreach ($user_cache as $index => $user) {
            echo '<tr>';
            echo '<td>'.$user['name'].'</td>';
            echo '<td>'.$user['uuid'].'</td>';
            echo '<td>'.$user['expiresOn'].'</td>';
            echo '</tr>';  
          }
        ?>
      </tbody>
    </table>
  </div>
</body>

</html>
