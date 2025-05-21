USE biblioteca;

-- Evitar errores por duplicados usando INSERT IGNORE
-- Insertar registros en la tabla autor (4 registros)
INSERT IGNORE INTO autor (autor_nombre, autor_nacionalidad, autor_fecha_nacimiento) VALUES
  ('Gabriel García Márquez', 'Colombiano', '1927-03-06'),
  ('Isabel Allende', 'Chilena', '1942-08-02'),
  ('Jorge Luis Borges', 'Argentino', '1899-08-24'),
  ('Octavio Paz', 'Mexicano', '1914-03-31');

-- Insertar registros en la tabla libro (4 registros)
INSERT IGNORE INTO libro (libro_titulo, libro_isbn, libro_copias_totales, libro_copias_disponibles) VALUES
  ('Cien años de soledad', '9780307474728', 5, 3),
  ('El amor en los tiempos del cólera', '9780307389732', 4, 4),
  ('Ficciones', '9788497596653', 6, 5),
  ('El laberinto de la soledad', '9789500815407', 3, 1);

-- Insertar registros en la tabla ejemplar (4 registros)
INSERT IGNORE INTO ejemplar (ejemplar_libro_id, ejemplar_estado) VALUES
  (1, 'disponible'),
  (1, 'prestado'),
  (2, 'disponible'),
  (3, 'en_mora');

-- Insertar registros en la tabla libroautor_libroautor (4 registros)
INSERT IGNORE INTO libroautor_libroautor (libroautor_libro_id, autor_id) VALUES
  (1, 1),
  (2, 1),
  (3, 3),
  (4, 4);

-- Insertar registros en la tabla prestatario (4 registros)
INSERT IGNORE INTO prestatario (prestatario_nombre, prestatario_identificacion) VALUES
  ('Carlos Mendoza', '123456'),
  ('Ana Torres', '234567'),
  ('Luis García', '345678'),
  ('Marta Suárez', '456789');

-- Insertar registros en la tabla rol (4 registros)
INSERT IGNORE INTO rol (rol_nombre) VALUES
  ('Administrador'),
  ('Bibliotecario'),
  ('Invitado'),
  ('Usuario_externo');

-- Insertar registros en la tabla usuario (4 registros)
INSERT IGNORE INTO usuario (usuario_nombre, usuario_email, usuario_contrasena, usuario_rol_id) VALUES
  ('Alejandro Martínez', 'alejandro@example.com', 'secret1', 1),
  ('Mariana Ruiz', 'mariana@example.com', 'secret2', 2),
  ('Santiago López', 'santiago@example.com', 'secret3', 3),
  ('Elena Pérez', 'elena@example.com', 'secret4', 4);

-- Insertar registros en la tabla prestamo (4 registros)
INSERT IGNORE INTO prestamo (prestamo_prestatario_id, prestamo_bibliotecario_id, prestamo_fecha_devolucion_prevista) VALUES
  (1, 2, '2025-05-24 00:00:00'),
  (2, 2, '2025-05-25 00:00:00'),
  (3, 2, '2025-05-26 00:00:00'),
  (4, 2, '2025-05-27 00:00:00');

-- Insertar registros en la tabla prestamoejemplar (4 registros)
INSERT IGNORE INTO prestamoejemplar (prestamoejemplar_prestamo_id, prestamoejemplar_ejemplar_id) VALUES
  (1, 1),
  (2, 2),
  (3, 3),
  (4, 4);
