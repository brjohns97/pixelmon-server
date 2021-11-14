<?php
    include '../navbar.php';
    echo '<title>Mods</title>';
    //echo 'This will soon show a list of all the mods currently on the server and possibly download links n shit.';
    $mod_file_paths = glob("/pixelmon/mods/*.jar");
    foreach ($mod_file_paths as $index => $mod_file_path) {
      $mods[$index]['name'] = basename($mod_file_path);
      $mods[$index]['download'] = '/mod_files/'.basename($mod_file_path);
    }
?>

<div class="container mt-3">
    <h2>Current mods on the server</h2>
    <table class="table">
      <thead>
        <tr>
          <th>Mod Name</th>
        </tr>
      </thead>
      <tbody>
        <?php
          foreach ($mods as $index => $mod) {
            echo '<tr>';
            echo '<td><a href="'.$mod['download'].'">'.$mod['name'].' </a></td>';
          }
        ?>
      </tbody>
    </table>
  </div>