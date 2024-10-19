
PDS - Trabajo Práctico - G14

Descripción

Este proyecto es un sistema de gestión de usuarios y registros, con diversas funcionalidades como autenticación de usuarios, registro, restablecimiento de contraseñas y registro de actividades de los usuarios. Incluye un panel de administración donde el administrador puede ver y gestionar los registros y usuarios.

Instrucciones de Instalación

Extraer los archivos suministrados y asegurarse que la estructura de carpetas sean
httdocs/pds/*.php
httdocs/pds/css/*.css

*Crear y Configurar la base de datos
   - Importa el archivo `pds.sql` en tu base de datos MySQL:
   - Tener en cuenta que para que la página funcione, la base de datos debe llamarse “pds”, la dirección de la misma debe ser “localhost” y el usuario de la misma debe ser “root” y contraseña en blanco (“”) o de lo contrario debes reconfigurar el archivo db.php en sus líneas 2,3 y 4 para que coincidan los parámetros con tu base de datos
   

servername = “localhost”
username = “root”
password = " "
dbname = "pds"

Instrucciones de Uso

Ejecutar la aplicación
   - Asegúrese de que su servidor Apache y MySQL estén en ejecución y funcionando correctamente. 
   - En el navegador coloque la siguiente dirección: http://localhost/pds/login.php

Desde aquí ya puedes utilizar la aplicación. Para su utilización te suministramos los siguientes usuarios para la utilización de la misma:


Usuarios: Administradores
usuario “ Admin ” / contraseña “ Admin1234! ”
usuario “ Diego ” / contraseña “ Diego1234! ”

Usuarios: Usuarios sin permisos especiales
Usuario “User” / contraseña “User1234!”


Funcionalidades aplicadas:

Desde login.php, puedes loguearte como un usuario ya existente en la base de datos, o usar los botones que redireccionan a registrar usuario nuevo, o reestablecer contraseña si las has olvidado. En login.php se hace una verificación de que exista tanto el usuario como la contraseña, si alguno de los dos campos es incorrecto, se mostrará un mensaje indicandolo, y si un usuario introduce la contraseña incorrectamente varias veces, ese usuario será bloqueado y no podrá loguearse hasta que un administrador lo desbloquee desde el panel de admin_dashboard

Una vez realizadas las autenticaciones y autorizaciones pertinentes, tras haber verificado la identidad del usuario pasamos al dashboard, en el caso del administrador será redireccionado al “admin_dashboard.php” y en el caso de los usuarios al “dashboard.php”. Allí se observará el nombre de usuario y el rol que este posee en el sistema el usuario verificado. 
El usuario Admin posee una diversidad de funcionalidades que puede realizar desde su dashboard. Presenta la posibilidad de visualizar los registros de logs (redirigiendo a log.php), realizar filtros por usuario, por email, por tipo de acción e intervalo de fechas. En el Panel de Administrador puede visualizar el listado de usuarios registrados con sus correspondientes email, rol y el estado en que se encuentra, pudiendo bloquear o desbloquear a los mismos según sea conveniente, ya sea por el bloqueo automático en razón de los ingresos erróneos de contraseña. El Panel de Usuario, nos muestra el listado de usuarios y los email con que se hayan registrado en el sistema, pudiendo aplicar filtros tanto por usuario como por email.



Desde register.php el nuevo usuario podrá crear una cuenta nueva, sin permisos especiales, ingresando un nombre de usuario único y un correo electrónico. La página web verifica que tanto el usuario como el correo electrónico no se encuentren registrados antes de crear el mismo, en el caso de que así fuese, indicará con un mensaje. El usuario deberá ingresar una contraseña que deberá integrarse de al menos 8 caracteres, en una mezcla de números, mayúsculas , minúsculas y caracteres especiales. Deberá reingresarla para confirmar que no haya habido errores de tipeo, y además 3 preguntas de seguridad que se utilizaran en caso de que el usuario olvide su contraseña. Todas las contraseñas ingresadas por el usuario serán hasheadas con salting en la base de datos para mayor seguridad.



Para el restablecimiento de las contraseñas se ejecuta password_reset.php, allí el usuario deberá colocar el correo electrónico con el que se haya registrado y contestar las tres preguntas de seguridad, cuyas respuestas se encuentran precargadas.Las respuestas se envían al formulario process_password_reset.php, si son incorrectas, se solicitaran nuevamente. En caso de hacerlo correctamente, será redireccionado a update_password.php, donde le permitirá establecer su nueva contraseña, con los requisitos ya indicados, debiendola reingresar para verificar su correcta escritura. Si resultan coincidentes, se mostrará un mensaje indicando “contraseña actualizada exitosamente”, y redirigirá a la pagina login.php , de lo contrario se mostrará un mensaje que reza “las contraseñas no coinciden” y permitirá ingresarlas nuevamente.




Estructura de archivos del proyecto

Archivos CSS

- **Carpeta CSS**Contiene estilos para las diferentes páginas del sistema.
    - `admin_dashboard.css`: Estilos para la página del panel de administración.
    - `dashboard.css`: Estilos para la página principal del usuario.
    - `log.css`: Estilos para la página de registro de actividad.
    - `login.css`: Estilos para la página de inicio de sesión.
    - `password_reset.css`: Estilos para la página de restablecimiento de contraseña.
    - `process_password_reset.css`: Estilos restablecimiento de contraseña.
    - `register.css`: Estilos para la página de registro de usuarios.
    - `update_password.css`: Estilos para la página de actualización de contraseña.

Archivos PHP

- **Funcionalidades principales**:
    - `admin_dashboard.php`: Página principal del administrador para gestionar el sistema.
    - `dashboard.php`: Panel de control del usuario con información relevante.
    - `db.php`: Maneja la conexión a la base de datos (MySQL).
    - `log.php`: Muestra los registros de actividad de los usuarios.
    - `login.php`: Procesa el inicio de sesión de los usuarios.
    - `logout.php`: Cierra la sesión del usuario.
    - `password_reset.php`: Página para restablecer la contraseña.
    - `process_password_reset.php`: Procesa solicitudes de restablecimiento de contraseña.
    - `register.php`: Permite registrar nuevos usuarios.
    - `update_password.php`: Actualiza la contraseña del usuario.

Scripts Auxiliares

    - `toggleConfirmPasswordVisibility.php` y `togglePasswordVisibility.php`: Manejan la visibilidad de las contraseñas en los campos de entrada del usuario en el registro.
