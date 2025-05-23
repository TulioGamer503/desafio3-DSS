<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Editar Canción</title></head>
<body>
  <h1>Editar Canción</h1>
  <?php if (!empty($errors)): ?>
    <ul style="color:red;">
      <?php foreach($errors as $e): ?><li><?=htmlspecialchars($e)?></li><?php endforeach; ?>
    </ul>
  <?php endif; ?>
  <form method="post" action="/desafio3-DSS/public/songs/update">
    <input type="hidden" name="id" value="<?=htmlspecialchars($song->id)?>">
    <label>Título:  <input type="text" name="titulo"  value="<?=htmlspecialchars($song->titulo)?>"></label><br>
    <label>Artista: <input type="text" name="artista" value="<?=htmlspecialchars($song->artista)?>"></label><br>
    <label>Año:     <input type="number" name="ano"   value="<?=htmlspecialchars($song->ano)?>"></label><br>
    <label>Enlace:  <input type="url" name="enlace" value="<?=htmlspecialchars($song->enlace)?>"></label><br>
    <button type="submit">Actualizar</button>
  </form>
  <p><a href="/desafio3-DSS/public/songs">← Volver</a></p>
</body>
</html>
