<main class="container">
  <div class="bg-light p-5 rounded">
    <h1>Bardøy-sundet</h1>
    <p class="lead">Tidspunkt: <?php echo SeaLevel::formatTime($bardoy['time']); ?>, tidevannet er <?php echo $currentSeaLevel['direction'] == 'increasing' ? 'stigende' : 'synkende'; ?></p>
    <p class="lead">Dybde: <?php echo $bardoy['depth']; ?> cm</p>
    <img class="img-fluid" src="images/bardoy-sundet.png">
  </div>
</main>