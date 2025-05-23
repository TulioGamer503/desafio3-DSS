<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Agregar Canción</title>
</head>
<body>
  <h1>Agregar Canción</h1>

  <?php if (!empty($errors)): ?>
    <ul style="color:red;">
      <?php foreach($errors as $e): ?>
        <li><?= htmlspecialchars($e) ?></li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>

  <form method="post" action="/desafio3-DSS/public/songs/store">
    <label>
      Título:  
      <input type="text" name="titulo" value="<?= htmlspecialchars($titulo  ?? '') ?>">
    </label><br>

    <label>
      Artista: 
      <input type="text" name="artista" value="<?= htmlspecialchars($artista ?? '') ?>">
    </label><br>

    <label>
      Álbum:   
      <input type="text" name="album" value="<?= htmlspecialchars($album   ?? '') ?>">
    </label><br>

    <label>
      Año:     
      <input type="number" name="ano" value="<?= htmlspecialchars($ano      ?? '') ?>">
    </label><br>

    <label>
      Enlace:  
      <input type="url" name="enlace" value="<?= htmlspecialchars($enlace   ?? '') ?>">
    </label><br>

    <button type="submit">Guardar</button>
  </form>

  <p><a href="/desafio3-DSS/public/songs">← Volver</a></p>
</body>
</html>
