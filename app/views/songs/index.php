<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Mis Canciones</title>
</head>
<body>
  <h1>Mis Canciones</h1>

  <?php if (!empty($_SESSION['success'])): ?>
    <p style="color:green;"><?= htmlspecialchars($_SESSION['success']) ?></p>
    <?php unset($_SESSION['success']); ?>
  <?php endif; ?>

  <p>
    <a href="/desafio3-DSS/public/songs/create">+ Nueva Canción</a> |
    <a href="/desafio3-DSS/public/logout">Cerrar Sesión</a>
  </p>

  <table border="1" cellpadding="5" cellspacing="0">
    <thead>
      <tr>
        <th>Título</th>
        <th>Artista</th>
        <th>Año</th>
        <th>Enlace</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody id="songs-list">
      <!-- Aquí se inyectarán las filas vía JS -->
      <tr id="no-songs-row" style="display:none;">
        <td colspan="5">No tienes canciones. ¡Agrega la primera!</td>
      </tr>
    </tbody>
  </table>

  <script>
    // La URL de tu endpoint REST (fuera de public/)
    const API_URL = '/desafio3-DSS/api/songs.php';

    async function loadSongs() {
      const tbody = document.getElementById('songs-list');
      const noSongsRow = document.getElementById('no-songs-row');
      try {
        const res = await fetch(API_URL);
        if (!res.ok) throw new Error('HTTP ' + res.status);
        const songs = await res.json();

        // Limpia tabla
        tbody.innerHTML = '';

        if (songs.length === 0) {
          noSongsRow.style.display = '';
          tbody.appendChild(noSongsRow);
          return;
        }

        songs.forEach(s => {
          const tr = document.createElement('tr');
          tr.innerHTML = `
            <td>${s.titulo}</td>
            <td>${s.artista}</td>
            <td>${s.ano}</td>
            <td>${s.enlace ? `<a href="${s.enlace}" target="_blank">Ver</a>` : ''}</td>
            <td>
              <a href="/desafio3-DSS/public/songs/edit?id=${s.id}">✎</a>
              <a href="/desafio3-DSS/public/songs/delete?id=${s.id}"
                 onclick="return confirm('¿Eliminar esta canción?');">🗑</a>
            </td>
          `;
          tbody.appendChild(tr);
        });
      } catch (err) {
        console.error('Error cargando canciones:', err);
        noSongsRow.textContent = 'Error cargando canciones.';
        noSongsRow.style.display = '';
        tbody.appendChild(noSongsRow);
      }
    }

    window.addEventListener('DOMContentLoaded', loadSongs);
  </script>
</body>
</html>
