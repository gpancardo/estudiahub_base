<form name="signup" method="post" action="../controllers/signup.php">
    <!-- Nombres -->
    <label for="nombres">Nombre(s)</label>
    <input type="text" id="nombres" name="nombres" required>
    
    <!-- Apellidos -->
    <label for="apellidos">Apellido(s)</label>
    <input type="text" id="apellidos" name="apellidos" required>
    
    <!-- Usuario -->
    <label for="usuario">Nombre de usuario</label>
    <input type="text" id="usuario" name="usuario" required>
    
    <!-- Dirección -->
    <label for="direccion">Dirección completa</label>
    <input type="text" id="direccion" name="direccion" required>
    
    <!-- Contraseña -->
    <label for="contrasena">Contraseña</label>
    <input type="password" id="contrasena" name="contrasena" required>
    
    <!-- Email -->
    <label for="email">E-mail personal</label>
    <input type="email" id="email" name="email" required>
    
    <!-- Teléfono -->
    <label for="telefono">Teléfono</label>
    <input type="tel" id="telefono" name="telefono"> <!-- Cambiado de "telephone" a "tel" -->
    
    <!-- Carrera -->
    <label for="carrera">Carrera</label>
    <div>
        <input type="radio" id="carrera1" name="carrera" value="Carrera1">
        <label for="carrera1">Carrera 1</label>
        
        <input type="radio" id="carrera2" name="carrera" value="Carrera2">
        <label for="carrera2">Carrera 2</label>
        
        <input type="radio" id="carrera3" name="carrera" value="Carrera3">
        <label for="carrera3">Carrera 3</label>
    </div>
    
    <!-- Campus -->
    <label for="campus">Campus u organización</label>
    <div>
        <input type="radio" id="campus1" name="campus" value="Campus1">
        <label for="campus1">Campus 1</label>
        
        <input type="radio" id="campus2" name="campus" value="Campus2">
        <label for="campus2">Campus 2</label>
        
        <input type="radio" id="campus3" name="campus" value="Campus3">
        <label for="campus3">Campus 3</label>
    </div>
    
    <!-- Botón de envío -->
    <input type="submit" value="Registrarse">
</form>