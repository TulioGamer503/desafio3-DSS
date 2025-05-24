<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Agregar Canción</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen">
  <div class="container mx-auto px-4 py-8 max-w-2xl">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
      <!-- Encabezado -->
      <div class="bg-indigo-600 px-6 py-4">
        <div class="flex items-center justify-between">
          <h1 class="text-2xl font-bold text-white">
            <i class="fas fa-music mr-2"></i>Agregar Canción
          </h1>
          <a href="/desafio3-DSS/public/songs" class="text-white hover:text-indigo-200 transition">
            <i class="fas fa-arrow-left mr-1"></i> Volver
          </a>
        </div>
      </div>

      <!-- Mensajes de error -->
      <div id="errors" class="px-6 py-4"></div>

      <!-- Formulario -->
      <form id="song-form" class="px-6 py-4 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-gray-700 text-sm font-medium mb-1">Título *</label>
            <input type="text" name="titulo" required
                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
          </div>

          <div>
            <label class="block text-gray-700 text-sm font-medium mb-1">Artista *</label>
            <input type="text" name="artista" required
                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-gray-700 text-sm font-medium mb-1">Álbum</label>
            <input type="text" name="album"
                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
          </div>

          <div>
            <label class="block text-gray-700 text-sm font-medium mb-1">Año</label>
            <input type="number" name="ano"
                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
          </div>
        </div>

        <div>
          <label class="block text-gray-700 text-sm font-medium mb-1">Enlace (URL)</label>
          <input type="url" name="enlace"
                 class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        <div class="pt-4">
          <button type="submit" 
                  class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md transition duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            <i class="fas fa-save mr-2"></i> Guardar Canción
          </button>
        </div>
      </form>
    </div>
  </div>

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
          errDiv.innerHTML = `
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4 rounded">
              <h3 class="text-red-700 font-medium">Errores encontrados:</h3>
              <ul class="list-disc list-inside text-red-600 mt-1">
                ${json.errors.map(e => `<li>${e}</li>`).join('')}
              </ul>
            </div>
          `;
        } else {
          // Éxito: redirigir a lista
          window.location = '/desafio3-DSS/public/songs';
        }
      } catch (err) {
        errDiv.innerHTML = `
          <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4 rounded">
            <p class="text-red-700">Error de conexión. Intente nuevamente.</p>
          </div>
        `;
        console.error(err);
      }
    });
  </script>
</body>
</html>