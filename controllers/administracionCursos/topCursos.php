<?php 
    require_once "../../db/conn.php";
    
    function topPorTema($tema){
        global $conn;
        
        $sql = "SELECT * FROM cursos 
                WHERE tema = :tema 
                ORDER BY fecha_creacion DESC 
                LIMIT 5";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':tema', $tema, SQLITE3_TEXT);
        $result = $stmt->execute();
        
        echo "<div class='cursos-tema'>";
        echo "<h3>Cursos más recientes de " . htmlspecialchars($tema) . "</h3>";
        echo "<div class='cursos-lista'>";
        
        $cursos_encontrados = false;
        
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $cursos_encontrados = true;
            echo "<div class='curso-card'>";
            echo "<h4>" . htmlspecialchars($row['titulo']) . "</h4>";
            echo "<p>" . htmlspecialchars($row['descripcion'] ?? '') . "</p>";
            echo "<p><strong>Instructor:</strong> " . htmlspecialchars($row['instructor'] ?? '') . "</p>";
            echo "<p><strong>Fecha:</strong> " . date('d/m/Y', strtotime($row['fecha_creacion'])) . "</p>";
            
            if (isset($row['precio'])) {
                echo "<p><strong>Precio:</strong> $" . number_format($row['precio'], 2) . "</p>";
            }
            
            echo "<a href='curso.php?id=" . $row['id'] . "' class='btn-ver-curso'>Ver Curso</a>";
            echo "</div>";
        }
        
        if (!$cursos_encontrados) {
            echo "<p>No se encontraron cursos para el tema: " . htmlspecialchars($tema) . "</p>";
        }
        
        echo "</div>";
        echo "</div>";
    }

    // Versión que retorna un array para mayor flexibilidad
    function topPorTemaArray($tema){
        global $conn;
        
        $sql = "SELECT * FROM cursos 
                WHERE tema = :tema 
                ORDER BY fecha_creacion DESC 
                LIMIT 5";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':tema', $tema, SQLITE3_TEXT);
        $result = $stmt->execute();
        
        $cursos = [];
        
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $cursos[] = $row;
        }
        
        return $cursos;
    }

    // Función para mostrar cursos de múltiples temas
    function topPorMultiplesTemas($temas){
        global $conn;
        
        if (!is_array($temas)) {
            $temas = [$temas];
        }
        
        $placeholders = str_repeat('?,', count($temas) - 1) . '?';
        
        $sql = "SELECT c.* 
                FROM cursos c
                INNER JOIN (
                    SELECT tema, MAX(fecha_creacion) as max_fecha
                    FROM cursos 
                    WHERE tema IN ($placeholders)
                    GROUP BY tema
                ) mc ON c.tema = mc.tema AND c.fecha_creacion = mc.max_fecha
                ORDER BY c.tema";
        
        $stmt = $conn->prepare($sql);
        
        // Bind de parámetros
        foreach ($temas as $index => $tema) {
            $stmt->bindValue($index + 1, $tema, SQLITE3_TEXT);
        }
        
        $result = $stmt->execute();
        
        echo "<div class='cursos-multiples-temas'>";
        echo "<h2>Cursos más recientes por tema</h2>";
        
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            echo "<div class='curso-tema'>";
            echo "<h3>Tema: " . htmlspecialchars($row['tema']) . "</h3>";
            echo "<div class='curso-card'>";
            echo "<h4>" . htmlspecialchars($row['titulo']) . "</h4>";
            echo "<p>" . htmlspecialchars($row['descripcion'] ?? '') . "</p>";
            echo "<p><strong>Fecha:</strong> " . date('d/m/Y', strtotime($row['fecha_creacion'])) . "</p>";
            echo "<a href='curso.php?id=" . $row['id'] . "' class='btn-ver-curso'>Ver Curso</a>";
            echo "</div>";
            echo "</div>";
        }
        
        echo "</div>";
    }
?>