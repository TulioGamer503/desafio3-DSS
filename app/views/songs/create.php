<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Agregar Canción</title>
</head>
<body>
  <h1>Agregar Canción</h1>

  <div id="errors" style="color:red;"></div>

  <form id="song-form">
    <label>
      Título:
      <input type="text" name="titulo">
    </label><br>

    <label>
      Artista:
      <input type="text" name="artista">
    </label><br>

    <label>
      Álbum:
      <input type="text" name="album">
    </label><br>

    <label>
      Año:
      <input type="number" name="ano">
    </label><br>

    <label>
      Enlace:
      <input type="url" name="enlace">
    </label><br>

    <button type="submit">Guardar</button>
  </form>

  <p><a href="/desafio3-DSS/public/songs">← Volver</a></p>

  <script>
    const API = '/desafio3-DSS/api/songs.php';
    const form = document.getElementById('song-form');
    const errDiv = document.getElementById('errors');

    form.addEventListener('submit', async e => {
      e.preventDefault();
      errDiv.innerHTML = '';

      const data = {
        titulo:  form.titulo.value.trim(),
        artista: form.artista.value.trim(),
        album:   form.album.value.trim(),
        ano:     form.ano.value.trim(),
        enlace:  form.enlace.value.trim()
      };

      try {
        const res = await fetch(API, {
          method: 'POST',
          headers: {'Content-Type':'application/json'},
          body: JSON.stringify(data)
        });
        const json = await res.json();

        if (!res.ok) {
          errDiv.innerHTML = '<ul>' +
            json.errors.map(e => `<li>${e}</li>`).join('') +
          '</ul>';
        } else {
          // Éxito: redirigir a lista
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
