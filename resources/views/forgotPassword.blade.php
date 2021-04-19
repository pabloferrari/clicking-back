
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Clicking</title>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    
                    <h3>Hola {{ $name }}</h3>

                    <p>Recibimos tu solicitud de reestablecer tu contraseña.</p>

                    <p>Este es tu nuevo acceso:</p>

                    <p>Email: {{ $email }}</p>
                    <p>Contraseña: {{ $password }}</p>

                    <br>
                    <p>Sólo estara disponible una vez. Cuando ingreses, dirigete a configuración de cuenta y vuelve a cambiarla.</p>
                    
                </div>
            </div>
        </div>
    </div>
</body>
</html>
