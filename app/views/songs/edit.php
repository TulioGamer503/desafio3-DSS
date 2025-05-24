<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Editar Canción</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
  <div class="bg-white rounded-lg shadow-md p-8 w-full max-w-md">
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold text-gray-800">
        <i class="fas fa-edit mr-2 text-indigo-600"></i>Editar Canción
      </h1>
      <a href="/desafio3-DSS/public/songs" class="text-gray-600 hover:text-gray-800">
        <i class="fas fa-arrow-left mr-1"></i> Volver
      </a>
    </div>

    <!-- Mensajes de error -->
    <div id="errors" class="mb-4"></div>

    <!-- Formulario -->
    <form id="edit-form" class="space-y-4">
      <input type="hidden" name="id" value="<?=htmlspecialchars($song->id)?>">

      <div>
        <label class="block text-gray-700 text-sm font-medium mb-1">Título *</label>
        <input type="text" name="titulo" value="<?=htmlspecialchars($song->titulo)?>" required
               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
      </div>

      <div>
        <label class="block text-gray-700 text-sm font-medium mb-1">Artista *</label>
        <input type="text" name="artista" value="<?=htmlspecialchars($song->artista)?>" required
               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
      </div>

      <div>
        <label class="block text-gray-700 text-sm font-medium mb-1">Álbum</label>
        <input type="text" name="album" value="<?=htmlspecialchars($song->album)?>"
               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
      </div>

      <div>
        <label class="block text-gray-700 text-sm font-medium mb-1">Año</label>
        <input type="number" name="ano" value="<?=htmlspecialchars($song->ano)?>"
               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
      </div>

      <div>
        <label class="block text-gray-700 text-sm font-medium mb-1">Enlace</label>
        <input type="url" name="enlace" value="<?=htmlspecialchars($song->enlace)?>"
               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
      </div>

      <button type="submit" 
              class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-md transition duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
        <i class="fas fa-save mr-2"></i> Actualizar Canción
      </button>
    </form>
  </div>

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
            errDiv.innerHTML = `
              <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded">
                <h3 class="text-red-700 font-medium">Errores encontrados:</h3>
                <ul class="list-disc list-inside text-red-600 mt-1">
                  ${json.errors.map(e => `<li>${e}</li>`).join('')}
                </ul>
              </div>
            `;
          } else {
            errDiv.innerHTML = `
              <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded text-red-700">
                ${json.error || 'Error desconocido.'}
              </div>
            `;
          }
        } else {
          // Mostrar notificación de éxito antes de redirigir
          const notification = document.createElement('div');
          notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-md shadow-lg flex items-center';
          notification.innerHTML = `
            <i class="fas fa-check-circle mr-2"></i> Canción actualizada correctamente
          `;
          document.body.appendChild(notification);
          
          setTimeout(() => {
            notification.remove();
            window.location = '/desafio3-DSS/public/songs';
          }, 1500);
        }
      } catch (err) {
        errDiv.innerHTML = `
          <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded text-red-700">
            Error de conexión. Intente nuevamente.
          </div>
        `;
        console.error(err);
      }
    });
  </script>
</body>
</html>