<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Editar Canción</title>
</head>
<body>
  <h1>Editar Canción</h1>

  <div id="errors" style="color:red;"></div>

  <form id="edit-form">
    <input type="hidden" name="id" value="<?=htmlspecialchars($song->id)?>">

    <!-- campos idénticos a create.php, pero con value="<?= $song->… ?>" -->
    <label>Título:
      <input type="text" name="titulo" value="<?=htmlspecialchars($song->titulo)?>">
    </label><br>
    <label>Artista:
      <input type="text" name="artista" value="<?=htmlspecialchars($song->artista)?>">
    </label><br>
    <label>Álbum:
      <input type="text" name="album" value="<?=htmlspecialchars($song->album)?>">
    </label><br>
    <label>Año:
      <input type="number" name="ano" value="<?=htmlspecialchars($song->ano)?>">
    </label><br>
    <label>Enlace:
      <input type="url" name="enlace" value="<?=htmlspecialchars($song->enlace)?>">
    </label><br>

    <button type="submit">Actualizar</button>
  </form>

  <p><a href="/desafio3-DSS/public/songs">← Volver</a></p>

  <script>
    const API = '/desafio3-DSS/api/songs.php';
    const form = document.getElementById('edit-form');
    const errDiv = document.getElementById('errors');

    form.addEventListener('submit', async e => {
      e.preventDefault();
      errDiv.innerHTML = '';

      const data = {
        id:      form.id.value,
        titulo:  form.titulo.value.trim(),
        artista: form.artista.value.trim(),
        album:   form.album.value.trim(),
        ano:     form.ano.value.trim(),
        enlace:  form.enlace.value.trim()
      };

      try {
        const res = await fetch(API, {
          method: 'PUT',
          headers: {'Content-Type':'application/json'},
          body: JSON.stringify(data)
        });
        const json = await res.json();

        if (!res.ok) {
          if (json.errors) {
            errDiv.innerHTML = '<ul>' +
              json.errors.map(e => `<li>${e}</li>`).join('') +
            '</ul>';
          } else {
            errDiv.textContent = json.error || 'Error desconocido.';
          }
        } else {
          window.location = '/desafio3-DSS/public/songs';
        }
      } catch (err) {
        errDiv.textContent = 'Error de conexión.';
        console.error(err);
      }
    });
  </script>
</body>
</html>
