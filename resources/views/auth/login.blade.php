<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - UDLA Horarios</title>
    <!-- Cargamos estilos de bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        /* Centramos la caja de login en medio de la pantalla */
        body {
            background-color: #f1f5f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .login-box {
            max-width: 440px;
            width: 100%;
        }
        .btn-udla {
            background-color: #8a1538;
            color: #ffffff;
            border: none;
        }
        .btn-udla:hover {
            background-color: #6d0f2b;
            color: #ffffff;
        }
    </style>
</head>
<body>

<div class="login-box">
    <div class="card shadow-sm border-0">
        <!-- Cabecera con el logo y nombre de la universidad -->
        <div class="card-header bg-dark text-white text-center py-4">
            <i class="bi bi-mortarboard-fill fs-1 text-danger"></i>
            <h5 class="fw-bold mb-0 mt-2">Universidad de Las Américas</h5>
            <small class="text-secondary">Gestión de Horarios Académicos</small>
        </div>

        <div class="card-body p-4">
            <!-- Mensaje de error si las credenciales fallan -->
            @if(session('error'))
                <div class="alert alert-danger small py-2">{{ session('error') }}</div>
            @endif
            <!-- Mensaje si acaba de cerrar sesion -->
            @if(session('success'))
                <div class="alert alert-success small py-2">{{ session('success') }}</div>
            @endif

            <!-- Formulario de inicio de sesion -->
            <form action="{{ route('login') }}" method="POST">
                @csrf

                <!-- Campo para el correo institucional -->
                <div class="mb-3">
                    <label for="email" class="form-label fw-bold small">Correo Electrónico</label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="usuario@udla.edu.ec" required autofocus>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Campo para la clave -->
                <div class="mb-3">
                    <label for="password" class="form-label fw-bold small">Contraseña</label>
                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="••••••••" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Boton para enviar los datos -->
                <button type="submit" class="btn btn-udla w-100 py-2 mb-3">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Iniciar Sesión
                </button>
            </form>

            <hr>

            <!-- Botones rapidos para probar los diferentes roles sin tener que escribir -->
            <div class="text-center small text-muted mb-2 fw-bold">Accesos rápidos de prueba por Rol:</div>
            <div class="d-grid gap-2">
                <!-- Llena los datos del usuario administrador -->
                <button type="button" class="btn btn-sm btn-outline-danger text-start d-flex justify-content-between align-items-center" onclick="setLogin('admin@udla.edu.ec', 'password123')">
                    <span><i class="bi bi-shield-lock-fill me-1"></i> Admin: admin@udla.edu.ec</span>
                    <span class="badge bg-danger">Acceso Total</span>
                </button>
                <!-- Llena los datos del profesor -->
                <button type="button" class="btn btn-sm btn-outline-primary text-start d-flex justify-content-between align-items-center" onclick="setLogin('profesor@udla.edu.ec', 'password123')">
                    <span><i class="bi bi-person-video3 me-1"></i> Profesor: profesor@udla.edu.ec</span>
                    <span class="badge bg-primary">Solo Lectura</span>
                </button>
                <!-- Llena los datos del estudiante -->
                <button type="button" class="btn btn-sm btn-outline-success text-start d-flex justify-content-between align-items-center" onclick="setLogin('estudiante@udla.edu.ec', 'password123')">
                    <span><i class="bi bi-person-badge me-1"></i> Alumno: estudiante@udla.edu.ec</span>
                    <span class="badge bg-success">Solo Lectura</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Esta funcion sencilla copia el correo y contrasena en los inputs
    function setLogin(correo, clave) {
        document.getElementById('email').value = correo;
        document.getElementById('password').value = clave;
    }
</script>
</body>
</html>
