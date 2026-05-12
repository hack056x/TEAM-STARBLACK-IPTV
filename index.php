<?php
// index.php - Login adaptado del proyecto funcional
require_once 'db.php';

if (isLoggedIn()) {
    if (checkExpiration($_SESSION['exp_date'])) {
        header('Location: checker.php');
        exit();
    } else {
        session_destroy();
    }
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Por favor complete todos los campos';
    } else {
        $db = (new Database())->getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindValue(':username', $username, SQLITE3_TEXT);
        $result = $stmt->execute();
        $user = $result->fetchArray(SQLITE3_ASSOC);
        
        if ($user) {
            if (password_verify($password, $user['password'])) {
                if (checkExpiration($user['exp_date'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['exp_date'] = $user['exp_date'];
                    
                    header('Location: checker.php');
                    exit();
                } else {
                    $error = 'Tu cuenta ha expirado. Contacta al administrador.';
                }
            } else {
                $error = 'Contraseña incorrecta';
            }
        } else {
            $error = 'Usuario no encontrado';
        }
        $db->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>IPTV CHECKER · TEAM STARBLACK</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container text-center mt-5">
        <div class="row">
            <div class="col-lg-4 d-block mx-auto mt-5">
                <div class="row">
                    <div class="col-xl-12 col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h3>IPTV TEAM STARBLACK</h3>
                                <p class="text-muted">Acceso al Checker</p>
                                
                                <?php if ($error): ?>
                                    <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
                                <?php endif; ?>
                                
                                <form method="post">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" name="username" placeholder="Usuario" required>
                                    </div>
                                    <div class="input-group mb-4">
                                        <input type="password" class="form-control" name="password" placeholder="Contraseña" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <button type="submit" name="login" class="btn btn-primary btn-block">
                                                <i class="fas fa-sign-in-alt"></i> Ingresar
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <div class="footer-info mt-3">
                                    <small><i class="fas fa-shield-alt"></i> IPTV CHECKER By @hacker056</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>