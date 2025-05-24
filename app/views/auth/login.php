<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
  <div class="bg-white rounded-lg shadow-md p-8 w-full max-w-md">
    <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">Iniciar Sesión</h1>

    <?php if (!empty($_SESSION['success'])): ?>
      <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded">
        <p class="text-green-700"><?=htmlspecialchars($_SESSION['success'])?></p>
      </div>
      <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
      <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
        <p class="text-red-700"><?=htmlspecialchars($error)?></p>
      </div>
    <?php endif; ?>

    <form method="post" action="/desafio3-DSS/public/login" class="space-y-4">
      <div>
        <label class="block text-gray-700 text-sm font-medium mb-1">Email</label>
        <input type="email" name="email" value="<?=htmlspecialchars($email??'')?>" 
               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>
      
      <div>
        <label class="block text-gray-700 text-sm font-medium mb-1">Contraseña</label>
        <input type="password" name="password" 
               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>
      
      <button type="submit" 
              class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
        Entrar
      </button>
    </form>
    
    <p class="text-center text-gray-600 mt-6">
      ¿No tienes cuenta? 
      <a href="/desafio3-DSS/public/register" class="text-blue-600 hover:text-blue-800 font-medium">Regístrate</a>
    </p>
  </div>
</body>
</html>