<?php
// admin.php - Panel de administración
require_once 'db.php';
requireAdmin();

$db = (new Database())->getConnection();
$message = '';
$error = '';

// Procesar acciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $username = trim($_POST['username'] ?? '');
                $password = $_POST['password'] ?? '';
                $exp_date = $_POST['exp_date'] ?? '';
                $role = $_POST['role'] ?? 'user';
                
                if (empty($username) || empty($password) || empty($exp_date)) {
                    $error = 'Todos los campos son obligatorios';
                } else {
                    // Verificar si existe
                    $check = $db->prepare("SELECT id FROM users WHERE username = :username");
                    $check->bindValue(':username', $username, SQLITE3_TEXT);
                    $result = $check->execute();
                    if ($result->fetchArray()) {
                        $error = 'El usuario ya existe';
                    } else {
                        $hashed = password_hash($password, PASSWORD_DEFAULT);
                        $stmt = $db->prepare("INSERT INTO users (username, password, exp_date, role) 
                                             VALUES (:username, :password, :exp_date, :role)");
                        $stmt->bindValue(':username', $username, SQLITE3_TEXT);
                        $stmt->bindValue(':password', $hashed, SQLITE3_TEXT);
                        $stmt->bindValue(':exp_date', $exp_date, SQLITE3_TEXT);
                        $stmt->bindValue(':role', $role, SQLITE3_TEXT);
                        
                        if ($stmt->execute()) {
                            $message = 'Usuario creado correctamente';
                        } else {
                            $error = 'Error al crear usuario';
                        }
                    }
                }
                break;
                
            case 'edit':
                $id = intval($_POST['id'] ?? 0);
                $exp_date = $_POST['exp_date'] ?? '';
                $role = $_POST['role'] ?? 'user';
                
                if (!empty($_POST['password'])) {
                    $hashed = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $stmt = $db->prepare("UPDATE users SET password = :password, exp_date = :exp_date, role = :role WHERE id = :id");
                    $stmt->bindValue(':password', $hashed, SQLITE3_TEXT);
                } else {
                    $stmt = $db->prepare("UPDATE users SET exp_date = :exp_date, role = :role WHERE id = :id");
                }
                $stmt->bindValue(':exp_date', $exp_date, SQLITE3_TEXT);
                $stmt->bindValue(':role', $role, SQLITE3_TEXT);
                $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
                
                if ($stmt->execute()) {
                    $message = 'Usuario actualizado';
                } else {
                    $error = 'Error al actualizar';
                }
                break;
                
            case 'delete':
                $id = intval($_POST['id'] ?? 0);
                if ($id == $_SESSION['user_id']) {
                    $error = 'No puedes eliminar tu propio usuario';
                } else {
                    $stmt = $db->prepare("DELETE FROM users WHERE id = :id");
                    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
                    if ($stmt->execute()) {
                        $message = 'Usuario eliminado';
                    } else {
                        $error = 'Error al eliminar';
                    }
                }
                break;
        }
    }
}

// Obtener usuarios
$users = $db->query("SELECT id, username, exp_date, role, created_at FROM users ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel TEAM STARBLACK</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .container { max-width: 1200px; margin-top: 30px; }
        .table { color: #cfdcff; }
        .table th { color: #b6d0ff; border-color: #2d4070; }
        .table td { border-color: #1f2d4a; }
        .form-control { background: #02061780; color: white; border-color: #364a76; }
        .form-control:focus { background: #020617; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-white"><i class="fas fa-crown"></i> TEAM STARBLACK</h2>
            <div>
                <a href="checker.php" class="btn btn-secondary">← Volver</a>
                <a href="logout.php" class="btn btn-danger">Salir</a>
            </div>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Formulario añadir usuario -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Añadir Usuario</h5>
                <form method="POST">
                    <input type="hidden" name="action" value="add">
                    <div class="row">
                        <div class="col-md-2">
                            <input type="text" name="username" class="form-control" placeholder="Usuario" required>
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="password" class="form-control" placeholder="Contraseña" required>
                        </div>
                        <div class="col-md-3">
                            <input type="date" name="exp_date" class="form-control" value="<?php echo date('Y-m-d', strtotime('+1 month')); ?>" required>
                        </div>
                        <div class="col-md-3">
                            <select name="role" class="form-control">
                                <option value="user">Usuario</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary">Crear</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Lista de usuarios -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Usuarios</h5>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Nivel</th>
                            <th>Vences</th>
                            <th>Creado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $users->fetchArray(SQLITE3_ASSOC)): ?>
                        <tr>
                            <td>#<?php echo $row['id']; ?></td>
                            <td><i class="fas fa-user"></i> <?php echo htmlspecialchars($row['username']); ?></td>
                            <td>
                                <span class="badge <?php echo $row['role'] === 'admin' ? 'badge-success' : 'badge-secondary'; ?>">
                                    <?php echo $row['role']; ?>
                                </span>
                            </td>
                            <td>
                                <?php 
                                    $exp = $row['exp_date'];
                                    $color = $exp < date('Y-m-d') ? 'text-danger' : 'text-success';
                                ?>
                                <span class="<?php echo $color; ?>">
                                    <i class="fas fa-calendar"></i> <?php echo $exp; ?>
                                </span>
                            </td>
                            <td><?php echo $row['created_at']; ?></td>
                            <td>
                                <button class="btn btn-sm btn-info" onclick="editar(<?php echo $row['id']; ?>, '<?php echo $row['exp_date']; ?>', '<?php echo $row['role']; ?>')">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <?php if ($row['id'] != $_SESSION['user_id']): ?>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Editar -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="background: #0d1424; color: white;">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Usuario</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="form-group">
                            <label>Nueva Contraseña (dejar vacío para no cambiar)</label>
                            <input type="text" name="password" class="form-control" placeholder="••••••••">
                        </div>
                        <div class="form-group">
                            <label>Fecha Expiración</label>
                            <input type="date" name="exp_date" id="edit_exp" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Rol</label>
                            <select name="role" id="edit_role" class="form-control">
                                <option value="user">Usuario</option>
                                <option value="admin">Administrador</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editar(id, exp, role) {
            $('#edit_id').val(id);
            $('#edit_exp').val(exp);
            $('#edit_role').val(role);
            $('#editModal').modal('show');
        }
    </script>
</body>
</html>