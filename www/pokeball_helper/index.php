<?php
    $self = '/pixelmon/www/pokeball_helper/';
    $files = glob($self.'/stats/*.{json}', GLOB_BRACE);
    $pokemons;
    foreach ($files as $file) {
        $pokemon = json_decode(file_get_contents($file), true);
        $pokemons[$pokemon['name']] = $pokemon;
    }

    $balls = [
        'Beast Ball' => "5x catch rate when used on Ultra Beasts. 0.1x for everything else.",
        'Cherish Ball' => "1× catch rate.",
        'Dive Ball' => "3.5× catch rate if the Pokémon is in water.",
        'Dream Ball' => "4x catch rate if the Pokémon is sleeping.",
        'Dusk Ball' => "3.5× catch rate in dark places.",
        'Fast Ball' => "4× catch rate on Pokémon with 100 base speed or more.",
        'Friend Ball' => "Sets a captured Pokémon's happiness to 200.",
        'Great Ball' => "1.5× catch rate.",
        'GS Ball' => "1× catch rate.",
        'Heal Ball' => "Fully heals HP and status of captured Pokémon.",
        'Heavy Ball' => "Increased catch rate on heavy Pokémon.",
        'Level Ball' => "Increased catch rate the lower the wild Pokémon's level is compared to the player's active Pokémon.",
        'Love Ball' => "8× catch rate if the wild Pokémon is the same species and opposite gender as the player's active Pokémon.",
        'Lure Ball' => "3× on Pokémon found via fishing.",
        'Luxury Ball' => "Causes the captured Pokémon to gain more happiness from happiness gains.",
        'Master Ball' => "Catches Pokémon without fail.",
        'Moon Ball' => "4× catch rate if Pokémon is in an evolutionary family involving a Moon Stone.",
        'Nest Ball' => "Increased catch rate on lower-leveled Pokémon.",
        'Net Ball' => "3× catch rate on Bug and Water-type Pokémon.",
        'Park Ball' => "Catches Pokémon without fail.",
        'Poké Ball' => "1× catch rate, most basic Poké Ball.",
        'Premier Ball' => "Causes the captured Pokémon to emit a red particle effect when sent out.",
        'Quick Ball' => "5× catch rate if used on the first turn of a battle.",
        'Repeat Ball' => "3.5× catch rate on species of Pokémon that the player already owns.",
        'Safari Ball' => "1.5× catch rate in Plains biomes.",
        'Sport Ball' => "1.5× catch rate on Bug-type Pokémon.",
        'Timer Ball' => "Increased catch rate the longer the battle is.",
        'Ultra Ball' => "2× catch rate."
    ];

?>

<!DOCTYPE html>
<html lang="en">
<title>Pokeball Helper</title>

<?php
include '../navbar.php'
?>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<div class="container mt-3">
<span class="sfrac nowrap" style="display:inline-block; vertical-align:-0.5em; font-size:85%; text-align:center;"><span style="display:block; line-height:1em; padding:0 0.1em;"><i>Rate</i>×<i>Ball</i>×<i>Status</i>(1-(⅔)(<span class="sfrac nowrap" style="display:inline-block; vertical-align:-0.5em; font-size:85%; text-align:center;"><span style="display:block; line-height:1em; padding:0 0.1em;"><i>HPCurrent</i></span><span style="display:none;">/</span><span style="display:block; line-height:1em; padding:0 0.1em; border-top:1px solid;"><i>HPMax</i></span></span>))</span><span style="display:none;">/</span><span style="display:block; line-height:1em; padding:0 0.1em; border-top:1px solid;">255</span></span>
  <div class="row g-3" onchange="calculate_probs()">
    <div class="col-md-2">
    <label for="pokemonName" class="form-label">Pokemon</label>
        <select id="pokemonName" class="form-control select2">
            <?php
                foreach ($pokemons as $pokemon) {
                    echo '<option>'.$pokemon['name'].'</option>';
                }
            ?>
    </select>
    </div>
    <div class="col-md-1">
    <label for="pokemonStatus" class="form-label">Status</label>
        <select id="pokemonStatus" class="form-control">
        <option>None</option>
        <option>Sleeping</option>
        <option>Frozen</option>
        <option>Paralyzed</option>
        <option>Burned</option>
        <option>Poisoned</option>
        </select>
    </div>
    <div class="col-md-1">
        <label for="pokemonHealth" class="form-label">% Health</label>
        <input type="number" step="0.1" class="form-control" id="pokemonHealth" value="100">
    </div>
    <div class="col-md-1">
        <label for="yourLevel" class="form-label">Your Level</label>
        <input type="number" step="1" class="form-control" id="yourLevel" value="50">
    </div>
    <div class="col-md-1">
        <label for="oppLevel" class="form-label">Enemy Level</label>
        <input type="number" step="1" class="form-control" id="oppLevel" value="50">
    </div>
    <div class="col-md-1">
        <label for="turnNumber" class="form-label">Current Turn</label>
        <input type="number" step="1" class="form-control" id="turnNumber" value="1">
    </div>
    <div class="col-md-3">
        <input class="form-check-input" type="checkbox" id="darkPlace">
        <label class="form-check-label" for="darkPlace">Is it in a dark place?</label>
        <br>
        <input class="form-check-input" type="checkbox" id="fishing">
        <label class="form-check-label" for="fishing">Found it fishing?</label>
        <br>
        <input class="form-check-input" type="checkbox" id="inWater">
        <label class="form-check-label" for="inWater">Found it in water?</label>
        <br>
        <input class="form-check-input" type="checkbox" id="plainsBiome">
        <label class="form-check-label" for="plainsBiome">Found it in the plains biome?</label>
        <br>
        <input class="form-check-input" type="checkbox" id="alreadyOwn">
        <label class="form-check-label" for="alreadyOwn">Already own it?</label>
        <br>
        <input class="form-check-input" type="checkbox" id="ssos">
        <label class="form-check-label" for="ssos">Same species and opposite sex?</label>
    </div>
  </div>




  <div class="container mt-3">
    <h2>Which ball is the best?</h2>
    <table class="table" id ="ballTable">
      <thead>
        <tr>
          <th onclick="sortTable(0)">Ball Type</th>
          <th onclick="sortTable(1)">Description</th>
          <th onclick="sortTable(2)">Probability</th>
        </tr>
      </thead>
      <tbody>
        <?php
          foreach ($balls as $ball_name => $desc) {
            $_ball_name = str_replace(' ','_',$ball_name);
            echo '<tr id = '.$_ball_name.'>';
            echo '<td><img src="../images/balls/Grid_'.$_ball_name.'.png"><a href="https://pixelmonmod.com/wiki/'.$_ball_name.'">'.$_ball_name.'</a></td>';
            echo '<td>'.$desc.'</td>';
            echo '<td>eat my ass</td>';
            echo '</tr>';
          }
        ?>
      </tbody>
    </table>
  </div>
</div>
<script>
$(document).ready( function () {
    calculate_probs();
});
    function get_status_mult (status) {
        if (status == 'None') {
            return 1;
        } else if (status == 'Frozen' || status == 'Sleeping') {
            return 2.5;
        } else {
            return 1.5;
        }
    }
    function calculate_probs () {
        var pokemons = <?php echo json_encode($pokemons); ?>;
        // LOTS OF VARS
        var prob_col = 2;
        var decimal_places = 5;
        var pokemon_name = document.getElementById('pokemonName').value;
        var pokemon_status = document.getElementById('pokemonStatus').value;
        var pokemon_health = Number(document.getElementById('pokemonHealth').value);
        var turn_number = Number(document.getElementById('turnNumber').value);
        var your_level = Number(document.getElementById('yourLevel').value);
        var opp_level = Number(document.getElementById('oppLevel').value);
        var pokemon = pokemons[pokemon_name];

        // DO SOME CHECKS
        if (pokemon_health > 100) pokemon_health = 100;
        if (pokemon_health < 0) pokemon_health = 0;
        document.getElementById('pokemonHealth').value = pokemon_health;
        if (turn_number < 1) turn_number = 1;
        document.getElementById('turnNumber').value = turn_number;
        if (your_level < 1) your_level = 1;
        if (opp_level < 1) opp_level = 1;
        document.getElementById('yourLevel').value = your_level;
        document.getElementById('oppLevel').value = opp_level;
        
        // BASIC FORMULA VARIABLES
        var rate = pokemon['catchRate'];
        var status = get_status_mult(pokemon_status);
        var percent_health = pokemon_health;

        // THIS IS USED A LOT
        function ball_formula (ball) {
            let health_multiplier = 1-(percent_health/100)*(2/3);
            let prob = (rate*ball*status*health_multiplier)/255;
            return prob;
        }

        // THIS IS USED ONCE
        function heavy_ball_formula (ball) {
            let modifier;
            if (pokemon['weight'] < 199.9) {
                modifier = -20;
            } else if (pokemon['weight'] <= 299.9) {
                modifier = 20;
            } else if (pokemon['weight'] <= 409.5) {
                modifier = 30;
            } else {
                modifier = 40;
            }
            let health_multiplier = 1-(percent_health/100)*(2/3);
            let prob = ((rate+modifier)*ball*status*health_multiplier)/255;
            return prob;
        }

        // SETTING CELL VALUES
        function set_ball_prob_cell (balltype, prob) {
            let row = document.getElementById(balltype+'_Ball');
            row.getElementsByTagName("td")[prob_col].innerHTML = prob.toFixed(decimal_places);
        }

        var ball;
        // BEAST BALL PROB
        ball = (pokemon['ultraBeast']) ? 5 : 1;
        set_ball_prob_cell('Beast', ball_formula(ball));

        // CHERISH BALL PROB
        ball = 1;
        set_ball_prob_cell('Cherish', ball_formula(ball));

        // DIVE BALL PROB
        ball = (document.getElementById('inWater').checked) ? 3.5 : 1;
        set_ball_prob_cell('Dive', ball_formula(ball));
        
        // DREAM BALL PROB
        ball = (pokemon_status == 'Sleeping') ? 4 : 1;
        set_ball_prob_cell('Dream', ball_formula(ball));

        // DUSK BALL PROB
        ball = (document.getElementById('darkPlace').checked) ? 3.5 : 1;
        set_ball_prob_cell('Dusk', ball_formula(ball));

        // FAST BALL PROB
        ball = (pokemon['baseSpeed'] > 100) ? 4 : 1;
        set_ball_prob_cell('Fast', ball_formula(ball));

        // FRIEND BALL PROB
        ball = 1;
        set_ball_prob_cell('Friend', ball_formula(ball));

        // GREAT BALL PROB
        ball = 1.5;
        set_ball_prob_cell('Great', ball_formula(ball));

        // GS BALL PROB
        ball = 1;
        set_ball_prob_cell('GS', ball_formula(ball));

        // HEAL BALL PROB
        ball = 1;
        set_ball_prob_cell('Heal', ball_formula(ball));

        // HEAVY BALL PROB
        ball = 1;
        set_ball_prob_cell('Heavy', heavy_ball_formula(ball));

        // LEVEL BALL PROB
        if (your_level <= opp_level) {
            ball = 1;
        } else if (your_level < 2*opp_level) {
            ball = 2;
        } else if (your_level < 4*opp_level) {
            ball = 4;
        } else {
            ball = 8;
        }
        set_ball_prob_cell('Level', ball_formula(ball));

        // LOVE BALL PROB
        ball = (document.getElementById('ssos').checked) ? 8 : 1;
        set_ball_prob_cell('Love', ball_formula(ball));

        // LURE BALL PROB
        ball = (document.getElementById('fishing').checked) ? 3 : 1;
        set_ball_prob_cell('Lure', ball_formula(ball));

        // LUXURY BALL PROB
        ball = 1;
        set_ball_prob_cell('Luxury', ball_formula(ball));

        // MASTER BALL PROB
        ball = 1;
        set_ball_prob_cell('Master', 1);

        // MOON BALL PROB
        ball = pokemon['usesMoonStone'] ? 4 : 1;
        set_ball_prob_cell('Moon', ball_formula(ball));

        // NEST BALL PROB
        ball = (41-opp_level)/10;
        if (ball < 1) ball = 1;
        set_ball_prob_cell('Nest', ball_formula(ball));

        // NET BALL PROB
        ball = (pokemon['bugType'] || pokemon['waterType']) ? 3 : 1; 
        set_ball_prob_cell('Net', ball_formula(ball));

        // PARK BALL PROB
        ball = 1; 
        set_ball_prob_cell('Park', 1);

        // POKE BALL PROB
        ball = 1;
        set_ball_prob_cell('Poké', ball_formula(ball));

        // PREMIER BALL PROB
        ball = 1; 
        set_ball_prob_cell('Premier', ball_formula(ball));

        // QUICK BALL PROB
        ball = (turn_number == 1) ? 5 : 1; 
        set_ball_prob_cell('Quick', ball_formula(ball));

        // REPEAT BALL PROB
        ball = (document.getElementById('alreadyOwn').checked) ? 3.5 : 1;
        set_ball_prob_cell('Repeat', ball_formula(ball));

        // SAFARI BALL PROB
        ball = (document.getElementById('plainsBiome').checked) ? 1.5 : 1;
        set_ball_prob_cell('Safari', ball_formula(ball));

        // SPORT BALL PROB
        ball = (pokemon['bugType']) ? 1.5 : 1; 
        set_ball_prob_cell('Sport', ball_formula(ball));

        // TIMER BALL PROB
        if (turn_number > 11) turn_number = 11;
        ball = 1+(turn_number-1)*0.3;
        set_ball_prob_cell('Timer', ball_formula(ball));

        // ULTRA BALL PROB
        ball = 2;
        set_ball_prob_cell('Ultra', ball_formula(ball));

        sortTable(2, true);
    }
</script>

<script>
    $('.select2').select2();
</script>
<script>
function sortTable(n, force_desc = false) {
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  if (force_desc) switchcount = 0;
  table = document.getElementById("ballTable");
  switching = true;
  //Set the sorting direction to ascending:
  dir = (force_desc) ? "desc" : "asc"; 
  /*Make a loop that will continue until
  no switching has been done:*/
  while (switching) {
    //start by saying: no switching is done:
    switching = false;
    rows = table.rows;
    /*Loop through all table rows (except the
    first, which contains table headers):*/
    for (i = 1; i < (rows.length - 1); i++) {
      //start by saying there should be no switching:
      shouldSwitch = false;
      /*Get the two elements you want to compare,
      one from current row and one from the next:*/
      x = rows[i].getElementsByTagName("TD")[n];
      y = rows[i + 1].getElementsByTagName("TD")[n];
      /*check if the two rows should switch place,
      based on the direction, asc or desc:*/
      if (dir == "asc") {
        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
          //if so, mark as a switch and break the loop:
          shouldSwitch= true;
          break;
        }
      } else if (dir == "desc") {
        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
          //if so, mark as a switch and break the loop:
          shouldSwitch = true;
          break;
        }
      }
    }
    if (shouldSwitch) {
      /*If a switch has been marked, make the switch
      and mark that a switch has been done:*/
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      //Each time a switch is done, increase this count by 1:
      switchcount ++;      
    } else {
      /*If no switching has been done AND the direction is "asc",
      set the direction to "desc" and run the while loop again.*/
      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
      }
    }
  }
}
</script>

</html>
