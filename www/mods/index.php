<?php
    include '../navbar.php';
    echo '<title>Mods</title>';
    //echo 'This will soon show a list of all the mods currently on the server and possibly download links n shit.';

    function humanFileSize($size,$unit="") {
      if( (!$unit && $size >= 1<<30) || $unit == "GB")
        return number_format($size/(1<<30),2)."GB";
      if( (!$unit && $size >= 1<<20) || $unit == "MB")
        return number_format($size/(1<<20),2)."MB";
      if( (!$unit && $size >= 1<<10) || $unit == "KB")
        return number_format($size/(1<<10),2)."KB";
      return number_format($size)." bytes";
    }
    
    
    $mod_file_paths = glob("/pixelmon/mods/*.jar");
    foreach ($mod_file_paths as $index => $mod_file_path) {
      $mods[$index]['name'] = basename($mod_file_path);
      $mods[$index]['download'] = '/mod_files/'.basename($mod_file_path);
      $mods[$index]['size'] = humanFileSize(filesize($mod_file_path));
    }
?>

<div class="container mt-3">
    <h2>Current mods on the server</h2>
    <table class="table">
      <thead>
        <tr>
        <th>Mod Name</th>
        <th>Size</th>
        </tr>
      </thead>
      <tbody>
        <?php
          foreach ($mods as $index => $mod) {
            echo '<tr>';
            echo '<td><a href="'.$mod['download'].'">'.$mod['name'].' </a></td>';
            echo '<td>'.$mod['size'].'</td>';
          }
        ?>
      </tbody>
    </table>
  </div>