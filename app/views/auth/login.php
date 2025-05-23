<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Login</title></head>
<body>
  <h1>Iniciar Sesión</h1>

  <?php if (!empty($_SESSION['success'])): ?>
    <p style="color:green;"><?=htmlspecialchars($_SESSION['success'])?></p>
    <?php unset($_SESSION['success']); ?>
  <?php endif; ?>

  <?php if (!empty($error)): ?>
    <p style="color:red;"><?=htmlspecialchars($error)?></p>
  <?php endif; ?>

  <form method="post" action="/desafio3-DSS/public/login">
    <label>Email: <input type="email" name="email" value="<?=htmlspecialchars($email??'')?>"></label><br>
    <label>Contraseña: <input type="password" name="password"></label><br>
    <button type="submit">Entrar</button>
  </form>
  <p>¿No tienes cuenta? <a href="/desafio3-DSS/public/register">Regístrate</a></p>
</body>
</html>
