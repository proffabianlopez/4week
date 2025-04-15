## 4Week Practica

# Como enviar un mail

## Deberán recrear la metodología de un sitio web en donde un usuario puede ingresar ya sea por sus credenciales, en el caso de no estar registrado podra completar un formulario para dicho caso. Y por último un caso de olvido de password.-

## Pensar en la opción de Bloqueo de intentos

## Trabajo opcional

## Se deberá crear una rama con el siguiente nombre: dev_formregistro_nombrealumno

## Diseño sugerido del archivo de datos para los usuarios, el mismo se deberá llamar Usuarios.dat

    - Email
    - Apellido
    - Nombre
    - Password (encriptado)
    - Codigo Verificacion
    - Fecha de activacion
    - Fecha ultima actividad registrada
    - Status (0= No validado 1=Activo 2=Solicita Password 3=Bloqueado 4=Inactivo)
    - Datos Opcionales que deseen almacenar

### El diseño del archivo config.dat

    - Casilla de Correo
    - Token
    - MAX Cantidad de intentos para validar usuarios

### Que debo tener en cuenta:

    - Debera tener un control de información de lo que sucede almacenado en un archivo que se llame log.dat

### Diseño optativo, sugerencias:

    - Fecha formato dd/mm/aaaa hh:mm:ss
    - Usuario
    - Acción (Tener algun tipo de codigo o leyenda para una fácil,rápida comprensión de lo sucedido en tiempo real).

### Les deseo un buen fin de semana y por favor practiquen.

### Suban las ramas en caso de consulta.
