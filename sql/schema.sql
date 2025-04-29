-- Crear la base de datos si no existe
#CREATE DATABASE IF NOT EXISTS language_courses;

-- Usar la base de datos
USE language_courses;

-- Crear tabla de clases
CREATE TABLE IF NOT EXISTS classes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    rating FLOAT NOT NULL DEFAULT 0.0 COMMENT 'Rating on a scale of 1-5',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_class_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Crear tabla de exámenes
CREATE TABLE IF NOT EXISTS exams (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type ENUM('selection', 'question_answer', 'completion') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_exam_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar datos de ejemplo para clases
INSERT INTO classes (name, rating) VALUES 
('Vocabulario sobre Trabajo en Inglés', 5.0),
('Conversaciones de Trabajo en Inglés', 5.0),
('Verbos relacionados con Trabajo', 4.5),
('Trabajo y Profesiones en Español', 4.8),
('Inglés Básico para Entrevistas de Trabajo', 4.7),
('Vocabulario de Cocina en Francés', 4.2),
('Gramática Avanzada en Alemán', 4.9),
('Pronunciación en Italiano', 4.3),
('Verbos Irregulares en Inglés', 4.6),
('Frases Comunes en Japonés', 4.8);

-- Insertar datos de ejemplo para exámenes
INSERT INTO exams (name, type) VALUES 
('Trabajos y ocupaciones en Inglés', 'selection'),
('Entrevista de Trabajo Simulada', 'question_answer'),
('Vocabulario de Trabajo en Francés', 'completion'),
('Evaluación de Nivel B2 - Trabajo', 'selection'),
('Verbos para Describir Trabajos', 'completion'),
('Test de Comprensión Lectora Avanzada', 'question_answer'),
('Prueba de Escritura de Emails Formales', 'completion'),
('Examen Final de Conversación', 'question_answer'),
('Quiz de Vocabulario Profesional', 'selection'),
('Prueba de Certificación Nivel C1', 'selection');