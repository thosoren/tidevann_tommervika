<main class="container">
  <div class="bg-light p-5 rounded">
    <h1>Stupebrettet i Tømmervika</h1>
    <p class="lead">Tidspunkt: <?php echo SeaLevel::formatTime($diveBoard['time']); ?>, tidevannet er <?php echo $currentSeaLevel['direction'] == 'increasing' ? 'stigende' : 'synkende'; ?></p>
    <p class="lead">Høyde til vannet: <?php echo $diveBoard['hight']; ?> cm</p>
    <p class="lead">Dybde fra overflaten til bunnen: <?php echo $diveBoard['depth']; ?> cm</p>    
    <img class="img-fluid" src="images/stupebrett.png">
  </div>
</main>