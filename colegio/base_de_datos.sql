-- =============================================
--  COLEGIO SAN ANDRÉS – Base de datos MySQL
--  Ejecuta este script en phpMyAdmin o MySQL
-- =============================================

-- 1. Crear la base de datos
CREATE DATABASE IF NOT EXISTS colegio_db
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE colegio_db;

-- 2. Tabla de estudiantes
CREATE TABLE IF NOT EXISTS estudiantes (
  id             INT          AUTO_INCREMENT PRIMARY KEY,
  nombres        VARCHAR(100) NOT NULL,
  apellidos      VARCHAR(100) NOT NULL,
  documento      VARCHAR(20)  NOT NULL UNIQUE,
  fecha_nac      DATE         NOT NULL,
  genero         CHAR(1)      NOT NULL COMMENT 'M=Masculino F=Femenino O=Otro',
  telefono       VARCHAR(20)  DEFAULT NULL,
  grado          TINYINT      NOT NULL COMMENT '1 a 11',
  grupo          CHAR(1)      NOT NULL COMMENT 'A, B o C',
  año_matricula  YEAR         NOT NULL DEFAULT 2025,
  estado         ENUM('activo','inactivo','retirado') NOT NULL DEFAULT 'activo',
  acudiente      VARCHAR(100) NOT NULL,
  tel_acudiente  VARCHAR(20)  NOT NULL,
  direccion      VARCHAR(200) DEFAULT NULL,
  created_at     TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  updated_at     TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Índices útiles para búsquedas rápidas
CREATE INDEX idx_apellidos  ON estudiantes(apellidos);
CREATE INDEX idx_documento  ON estudiantes(documento);
CREATE INDEX idx_grado      ON estudiantes(grado);
CREATE INDEX idx_estado     ON estudiantes(estado);

-- 4. Datos de ejemplo
INSERT INTO estudiantes
  (nombres, apellidos, documento, fecha_nac, genero, telefono, grado, grupo, año_matricula, estado, acudiente, tel_acudiente, direccion)
VALUES
  ('Santiago', 'Ramírez Torres', '1001234501', '2010-03-15', 'M', '3001234567', 8, 'A', 2025, 'activo',  'Carmen Torres',  '3009876543', 'Calle 12 # 4-30'),
  ('Valentina', 'Gómez Herrera', '1001234502', '2011-07-22', 'F', '3102345678', 7, 'B', 2025, 'activo',  'Luis Gómez',     '3108765432', 'Carrera 5 # 10-20'),
  ('Juan David', 'Pérez Mora',   '1001234503', '2009-11-05', 'M', NULL,         9, 'A', 2025, 'activo',  'Ana Mora',       '3203456789', 'Calle 8 # 2-15'),
  ('Isabella',  'Castro Ruiz',   '1001234504', '2012-01-30', 'F', '3154567890', 6, 'C', 2025, 'inactivo','Pedro Castro',   '3154321098', 'Avenida 3 # 5-40'),
  ('Nicolás',   'Vargas López',  '1001234505', '2008-09-18', 'M', '3005678901', 10,'B', 2025, 'activo',  'Claudia López',  '3005432109', 'Calle 15 # 8-60');

-- Verificar
SELECT id, nombres, apellidos, grado, grupo, estado FROM estudiantes;
