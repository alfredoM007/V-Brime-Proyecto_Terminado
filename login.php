// Normalizar/validar entrada
$user = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
$pass = $_POST['contrasena'] ?? '';

if ($user === '' || $pass === '') {
  echo json_encode(['success' => false, 'message' => 'Usuario y contraseña requeridos']);
  exit;
}

// Hint: si admites correo o nombre, conviene comparar sin espacios extras y
// con un query que devuelva solo 1 fila.
try {
  $stmt = $pdo->prepare("
    SELECT id, nombre, correo, contrasena, rol
    FROM usuarios
    WHERE correo = :u OR nombre = :u
    LIMIT 1
  ");
  $stmt->execute(['u' => $user]);
  $u = $stmt->fetch(PDO::FETCH_ASSOC);

  // Verificar credenciales
  if ($u && password_verify($pass, $u['contrasena'])) {
    // Mitigar 'session fixation'
    if (session_status() === PHP_SESSION_ACTIVE) {
      session_regenerate_id(true);
    }

    $_SESSION['usuario_id'] = $u['id'];
    $_SESSION['usuario_nombre'] = $u['nombre'];
    $_SESSION['usuario_rol'] = $u['rol'];

    echo json_encode([
      'success' => true,
      'message' => 'Inicio de sesión exitoso',
      'rol' => $u['rol']
    ]);
  } else {
    echo json_encode(['success' => false, 'message' => 'Credenciales inválidas']);
  }
} catch (PDOException $e) {
  echo json_encode(['success' => false, 'message' => 'Error en el servidor']);
}
