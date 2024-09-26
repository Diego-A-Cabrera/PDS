<script>
    function togglePasswordVisibility(fieldId, iconElement) {
        // Obtener el campo de entrada de contraseña por su ID
        var passwordField = document.getElementById(fieldId);

        // Asegúrate de que el campo existe antes de hacer algo
        if (passwordField) {
            // Cambiar entre los tipos 'password' y 'text'
            if (passwordField.type === "password") {
                passwordField.type = "text";
                iconElement.textContent = "🙈";  // Cambia el ícono
            } else {
                passwordField.type = "password";
                iconElement.textContent = "👁️";  // Cambia el ícono
            }
        } else {
            console.error(`No se encontró el campo con ID ${fieldId}`);
        }
    }
</script>