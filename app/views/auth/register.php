<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Registro</title></head>
<body>
  <h1>Registro</h1>

  <?php if (!empty($errors)): ?>
    <ul style="color:red;">
      <?php foreach($errors as $e): ?>
        <li><?=htmlspecialchars($e)?></li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>

  <form method="post" action="/desafio3-DSS/public/register">
    <label>Usuario: <input type="text" name="username" value="<?=htmlspecialchars($username??'')?>"></label><br>
    <label>Email:   <input type="email" name="email" value="<?=htmlspecialchars($email??'')?>"></label><br>
    <label>Contraseña: <input type="password" name="password"></label><br>
    <button type="submit">Registrarme</button>
  </form>
  <p>¿Ya tienes cuenta? <a href="/desafio3-DSS/public/login">Entra aquí</a></p>
</body>
</html>
