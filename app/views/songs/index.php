<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Mis Canciones</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen">
  <div class="container mx-auto px-4 py-8">
    <!-- Encabezado -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
      <h1 class="text-3xl font-bold text-gray-800">
        <i class="fas fa-music mr-2 text-indigo-600"></i>Mis Canciones
      </h1>
      
      <div class="flex space-x-4 mt-4 md:mt-0">
        <a href="/desafio3-DSS/public/songs/create" 
           class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md transition flex items-center">
          <i class="fas fa-plus mr-2"></i> Nueva Canción
        </a>
        <a href="/desafio3-DSS/public/logout" 
           class="text-gray-600 hover:text-gray-800 px-4 py-2 rounded-md transition flex items-center">
          <i class="fas fa-sign-out-alt mr-2"></i> Cerrar Sesión
        </a>
      </div>
    </div>

    <!-- Mensaje de éxito -->
    <?php if (!empty($_SESSION['success'])): ?>
      <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded">
        <p class="text-green-700"><?= htmlspecialchars($_SESSION['success']) ?></p>
      </div>
      <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <!-- Tabla de canciones -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Título</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Artista</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Álbum</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Año</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Enlace</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
            </tr>
          </thead>
          <tbody id="songs-list" class="bg-white divide-y divide-gray-200">
            <tr id="no-songs-row" class="hidden">
              <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                No tienes canciones. ¡Agrega la primera!
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script>
    const API_URL = '/desafio3-DSS/api/songs.php';

    async function loadSongs() {
      const tbody = document.getElementById('songs-list');
      const noSongsRow = document.getElementById('no-songs-row');
      try {
        const res = await fetch(API_URL);
        if (!res.ok) throw new Error('HTTP ' + res.status);
        const songs = await res.json();

        tbody.innerHTML = '';
        if (songs.length === 0) {
          noSongsRow.classList.remove('hidden');
          tbody.appendChild(noSongsRow);
          return;
        }

        songs.forEach(s => {
          const tr = document.createElement('tr');
          tr.dataset.id = s.id;
          tr.className = 'hover:bg-gray-50 transition';
          tr.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm font-medium text-gray-900">${s.titulo}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-gray-500">${s.artista}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-gray-500">${s.album||'-'}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-gray-500">${s.ano||'-'}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              ${s.enlace ? `
                <a href="${s.enlace}" target="_blank" 
                   class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                  <i class="fas fa-external-link-alt mr-1"></i> Ver
                </a>
              ` : '<span class="text-gray-400 text-sm">-</span>'}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
              <a href="/desafio3-DSS/public/songs/edit?id=${s.id}" 
                 class="text-indigo-600 hover:text-indigo-900 mr-4"
                 title="Editar">
                <i class="fas fa-edit"></i>
              </a>
              <a href="#" class="delete-btn text-red-600 hover:text-red-900"
                 title="Eliminar">
                <i class="fas fa-trash"></i>
              </a>
            </td>
          `;
          tbody.appendChild(tr);
        });

      } catch (err) {
        console.error('Error cargando canciones:', err);
        noSongsRow.innerHTML = `
          <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-red-500">
            <i class="fas fa-exclamation-circle mr-2"></i>Error cargando canciones
          </td>
        `;
        noSongsRow.classList.remove('hidden');
        tbody.appendChild(noSongsRow);
      }
    }

    // Manejador de clicks para borrado
    document.addEventListener('click', async e => {
      if (e.target.closest('.delete-btn')) {
        e.preventDefault();
        const tr = e.target.closest('tr');
        const id = tr.dataset.id;
        
        if (!confirm('¿Estás seguro de eliminar esta canción?')) return;

        try {
          const res = await fetch(API_URL, {
            method: 'DELETE',
            headers: {'Content-Type':'application/json'},
            body: JSON.stringify({id})
          });
          
          if (!res.ok) throw new Error('HTTP ' + res.status);
          
          const json = await res.json();
          console.log(json.message);
          
          // Mostrar notificación de éxito
          const notification = document.createElement('div');
          notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-md shadow-lg';
          notification.innerHTML = `
            <i class="fas fa-check-circle mr-2"></i> Canción eliminada correctamente
          `;
          document.body.appendChild(notification);
          
          setTimeout(() => notification.remove(), 3000);
          
          // Recargar la lista
          loadSongs();
        } catch (err) {
          console.error('Error eliminando canción:', err);
          alert('No se pudo eliminar la canción. Intente nuevamente.');
        }
      }
    });

    window.addEventListener('DOMContentLoaded', loadSongs);
  </script>
</body>
</html>