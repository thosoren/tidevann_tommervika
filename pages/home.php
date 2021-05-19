<main class="container">
  <div class="bg-light p-5 rounded">
    <h1>Flo og fjære i Tømmervika</h1>
    <p class="lead">Dato: <?php echo date('d.m.Y'); ?></p>
    
  </div>

  <table class="table">
  <thead>
    <tr>
      <th scope="col">Tidspunkt</th>
      <th scope="col">Flo/fjære</th>
      <th scope="col">Høyde</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($tides as $tide) { ?>
    <tr>
      <th scope="row"><?php echo SeaLevel::formatTime($tide['time']); ?></th>
      <td><?php echo $tide['flag'] == "high" ? "Flo" : "Fjære"; ?></td>
      <td><?php echo $tide['value']; ?> cm</td>
    </tr>
<?php } ?>
  </tbody>
</table>
</main>