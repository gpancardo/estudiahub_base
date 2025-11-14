<?php

function loadEnv($filePath) {
    if (file_exists($filePath)) {
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) continue;
            putenv(trim($line));
        }
    }
}

$dbPath = __DIR__ . '/../base.sqlite';

try {
    // Establish SQLite connection
    $pdo = new PDO("sqlite:$dbPath");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Enable foreign keys for SQLite
    $pdo->exec("PRAGMA foreign_keys = ON");

    // Ejecutar cada CREATE TABLE por separado
    $tableStatements = [
        "CREATE TABLE if not exists usuarios (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nombres TEXT NOT NULL,
    apellidos TEXT NOT NULL,
    usuario TEXT NOT NULL UNIQUE,
    direccion TEXT NOT NULL,
    contrasena TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    telefono TEXT,
    carrera TEXT NOT NULL,
    campus TEXT NOT NULL,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
);
        
        CREATE TABLE if not exists cursos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    titulo TEXT NOT NULL,
    descripcion TEXT,
    duracion_horas INTEGER,
    nivel TEXT CHECK(nivel IN ('principiante', 'intermedio', 'avanzado')),
    precio DECIMAL(10,2),
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    activo BOOLEAN DEFAULT 1
);

-- Nueva tabla para tags temáticos
CREATE TABLE if not exists tags (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre TEXT NOT NULL UNIQUE,
    descripcion TEXT,
    color TEXT DEFAULT '#007bff'
);

-- Tabla de relación muchos a muchos entre cursos y tags
CREATE TABLE if not exists  curso_tags (
    curso_id INTEGER,
    tag_id INTEGER,
    PRIMARY KEY (curso_id, tag_id),
    FOREIGN KEY (curso_id) REFERENCES cursos(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
);

-- Tabla de certificados profesionales
CREATE TABLE if not exists certificados_profesionales (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre TEXT NOT NULL,
    descripcion TEXT,
    duracion_estimada_horas INTEGER,
    requisitos TEXT,
    precio_certificado DECIMAL(10,2),
    activo BOOLEAN DEFAULT 1,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de relación entre certificados y cursos (serie de cursos)
CREATE TABLE if not exists certificado_cursos (
    certificado_id INTEGER,
    curso_id INTEGER,
    orden INTEGER NOT NULL, -- Para definir el orden de los cursos en el certificado
    obligatorio BOOLEAN DEFAULT 1,
    PRIMARY KEY (certificado_id, curso_id),
    FOREIGN KEY (certificado_id) REFERENCES certificados_profesionales(id) ON DELETE CASCADE,
    FOREIGN KEY (curso_id) REFERENCES cursos(id) ON DELETE CASCADE
);

-- Tabla de quizzes para certificados
CREATE TABLE if not exists certificado_quizzes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    certificado_id INTEGER NOT NULL,
    titulo TEXT NOT NULL,
    descripcion TEXT,
    duracion_minutos INTEGER DEFAULT 60,
    intentos_permitidos INTEGER DEFAULT 3,
    puntaje_minimo_aprobacion INTEGER DEFAULT 70,
    activo BOOLEAN DEFAULT 1,
    FOREIGN KEY (certificado_id) REFERENCES certificados_profesionales(id) ON DELETE CASCADE
);

-- Tabla de preguntas para los quizzes
CREATE TABLE if not exists quiz_preguntas (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    quiz_id INTEGER NOT NULL,
    pregunta TEXT NOT NULL,
    tipo_pregunta TEXT CHECK(tipo_pregunta IN ('opcion_multiple', 'multiple_seleccion', 'verdadero_falso')) DEFAULT 'opcion_multiple',
    puntaje INTEGER DEFAULT 1,
    orden INTEGER NOT NULL,
    FOREIGN KEY (quiz_id) REFERENCES certificado_quizzes(id) ON DELETE CASCADE
);

-- Tabla de opciones para las preguntas
CREATE TABLE if not exists quiz_opciones (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    pregunta_id INTEGER NOT NULL,
    texto_opcion TEXT NOT NULL,
    es_correcta BOOLEAN DEFAULT 0,
    orden INTEGER NOT NULL,
    FOREIGN KEY (pregunta_id) REFERENCES quiz_preguntas(id) ON DELETE CASCADE
);

-- Tabla para registrar usuarios que obtienen certificados
CREATE TABLE if not exists usuario_certificados (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    usuario_id INTEGER NOT NULL, -- Asumiendo que ya tienes una tabla de usuarios
    certificado_id INTEGER NOT NULL,
    quiz_id INTEGER NOT NULL,
    fecha_completado DATETIME DEFAULT CURRENT_TIMESTAMP,
    puntaje_obtenido DECIMAL(5,2),
    aprobado BOOLEAN DEFAULT 0,
    fecha_emision DATETIME,
    codigo_certificado TEXT UNIQUE,
    FOREIGN KEY (certificado_id) REFERENCES certificados_profesionales(id),
    FOREIGN KEY (quiz_id) REFERENCES certificado_quizzes(id)
);

-- Tabla para registrar progreso de usuarios en certificados
CREATE TABLE if not exists usuario_certificado_progreso (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    usuario_id INTEGER NOT NULL,
    certificado_id INTEGER NOT NULL,
    curso_id INTEGER NOT NULL,
    completado BOOLEAN DEFAULT 0,
    fecha_completado DATETIME,
    progreso_porcentaje INTEGER DEFAULT 0,
    FOREIGN KEY (certificado_id) REFERENCES certificados_profesionales(id),
    FOREIGN KEY (curso_id) REFERENCES cursos(id),
    UNIQUE(usuario_id, certificado_id, curso_id)
);"
    ];

    // Ejecutar cada sentencia por separado
    foreach ($tableStatements as $sql) {
        $pdo->exec($sql);
    }

    header("Location: ../views/ofertaCursos.php");

} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>