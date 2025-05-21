-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema biblioteca
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS biblioteca ;

-- -----------------------------------------------------
-- Schema biblioteca
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS biblioteca DEFAULT CHARACTER SET utf8mb4 ;
USE biblioteca ;

-- -----------------------------------------------------
-- Table biblioteca.autor
-- -----------------------------------------------------
DROP TABLE IF EXISTS biblioteca.autor ;

CREATE TABLE IF NOT EXISTS biblioteca.autor (
  autor_id INT(11) NOT NULL AUTO_INCREMENT,
  autor_nombre VARCHAR(100) NOT NULL,
  autor_nacionalidad VARCHAR(50) NULL DEFAULT NULL,
  autor_fecha_nacimiento DATE NULL DEFAULT NULL,
  PRIMARY KEY (autor_id),
  UNIQUE INDEX nombre (autor_nombre ASC) ,
  INDEX idx_autor_nombre (autor_nombre ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table biblioteca.libro
-- -----------------------------------------------------
DROP TABLE IF EXISTS biblioteca.libro ;

CREATE TABLE IF NOT EXISTS biblioteca.libro (
  libro_id INT(11) NOT NULL AUTO_INCREMENT,
  libro_titulo VARCHAR(255) NOT NULL,
  libro_isbn VARCHAR(13) NOT NULL,
  libro_copias_totales INT(11) NULL DEFAULT 0,
  libro_copias_disponibles INT(11) NULL DEFAULT 0,
  PRIMARY KEY (libro_id),
  UNIQUE INDEX isbn (libro_isbn ASC) ,
  INDEX idx_libro_titulo (libro_titulo ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table biblioteca.ejemplar
-- -----------------------------------------------------
DROP TABLE IF EXISTS biblioteca.ejemplar ;

CREATE TABLE IF NOT EXISTS biblioteca.ejemplar (
  ejemplar_id INT(11) NOT NULL AUTO_INCREMENT,
  ejemplar_libro_id INT(11) NOT NULL,
  ejemplar_estado ENUM('disponible', 'prestado', 'en_mora', 'perdido') NULL DEFAULT 'disponible',
  PRIMARY KEY (ejemplar_id),
  INDEX libro_id (ejemplar_libro_id ASC) ,
  INDEX idx_ejemplar_estado (ejemplar_estado ASC) ,
  CONSTRAINT ejemplar_ibfk_1
    FOREIGN KEY (ejemplar_libro_id)
    REFERENCES biblioteca.libro (libro_id)
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table biblioteca.libroautor_libroautor
-- -----------------------------------------------------
DROP TABLE IF EXISTS biblioteca.libroautor_libroautor ;

CREATE TABLE IF NOT EXISTS biblioteca.libroautor_libroautor (
  libroautor_id INT NOT NULL  AUTO_INCREMENT,
  libroautor_libro_id INT(11) NOT NULL,
  autor_id INT(11) NOT NULL,
  INDEX autor_id (autor_id ASC) ,
  PRIMARY KEY (libroautor_id),
  CONSTRAINT libroautor_ibfk_1
    FOREIGN KEY (libroautor_libro_id)
    REFERENCES biblioteca.libro (libro_id)
    ON DELETE CASCADE,
  CONSTRAINT libroautor_ibfk_2
    FOREIGN KEY (autor_id)
    REFERENCES biblioteca.autor (autor_id)
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table biblioteca.prestatario
-- -----------------------------------------------------
DROP TABLE IF EXISTS biblioteca.prestatario ;

CREATE TABLE IF NOT EXISTS biblioteca.prestatario (
  prestatario_id INT(11) NOT NULL AUTO_INCREMENT,
  prestatario_nombre VARCHAR(100) NOT NULL,
  prestatario_identificacion VARCHAR(20) NOT NULL,
  prestatario_fecha_creacion DATETIME NULL DEFAULT CURRENT_TIMESTAMP(),
  PRIMARY KEY (prestatario_id),
  UNIQUE INDEX identificacion (prestatario_identificacion ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table biblioteca.rol
-- -----------------------------------------------------
DROP TABLE IF EXISTS biblioteca.rol ;

CREATE TABLE IF NOT EXISTS biblioteca.rol (
  rol_id INT(11) NOT NULL AUTO_INCREMENT,
  rol_nombre VARCHAR(50) NOT NULL,
  PRIMARY KEY (rol_id),
  UNIQUE INDEX nombre (rol_nombre ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table biblioteca.usuario
-- -----------------------------------------------------
DROP TABLE IF EXISTS biblioteca.usuario ;

CREATE TABLE IF NOT EXISTS biblioteca.usuario (
  usuario_id INT(11) NOT NULL AUTO_INCREMENT,
  usuario_nombre VARCHAR(100) NOT NULL,
  usuario_email VARCHAR(100) NOT NULL,
  usuario_contrasena VARCHAR(255) NOT NULL,
  usuario_rol_id INT(11) NOT NULL,
  usuario_fecha_registro DATETIME NULL DEFAULT CURRENT_TIMESTAMP(),
  PRIMARY KEY (usuario_id),
  UNIQUE INDEX email (usuario_email ASC) ,
  INDEX rol_id (usuario_rol_id ASC) ,
  CONSTRAINT usuario_ibfk_1
    FOREIGN KEY (usuario_rol_id)
    REFERENCES biblioteca.rol (rol_id)
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table biblioteca.prestamo
-- -----------------------------------------------------
DROP TABLE IF EXISTS biblioteca.prestamo ;

CREATE TABLE IF NOT EXISTS biblioteca.prestamo (
  prestamo_id INT(11) NOT NULL AUTO_INCREMENT,
  prestamo_prestatario_id INT(11) NOT NULL,
  prestamo_bibliotecario_id INT(11) NOT NULL,
  prestamo_fecha_prestamo DATETIME NULL DEFAULT CURRENT_TIMESTAMP(),
  prestamo_fecha_devolucion_prevista DATETIME NOT NULL,
  prestamo_fecha_devolucion_real DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (prestamo_id),
  INDEX prestatario_id (prestamo_prestatario_id ASC) ,
  INDEX bibliotecario_id (prestamo_bibliotecario_id ASC) ,
  CONSTRAINT prestamo_ibfk_1
    FOREIGN KEY (prestamo_prestatario_id)
    REFERENCES biblioteca.prestatario (prestatario_id),
  CONSTRAINT prestamo_ibfk_2
    FOREIGN KEY (prestamo_bibliotecario_id)
    REFERENCES biblioteca.usuario (usuario_id))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table biblioteca.prestamoejemplar
-- -----------------------------------------------------
DROP TABLE IF EXISTS biblioteca.prestamoejemplar ;

CREATE TABLE IF NOT EXISTS biblioteca.prestamoejemplar (
  prestamoejemplar_id INT NOT NULL  AUTO_INCREMENT,
  prestamoejemplar_prestamo_id INT(11) NOT NULL,
  prestamoejemplar_ejemplar_id INT(11) NOT NULL,
  INDEX ejemplar_id (prestamoejemplar_ejemplar_id ASC) ,
  PRIMARY KEY (prestamoejemplar_id),
  CONSTRAINT prestamoejemplar_ibfk_1
    FOREIGN KEY (prestamoejemplar_prestamo_id)
    REFERENCES biblioteca.prestamo (prestamo_id)
    ON DELETE CASCADE,
  CONSTRAINT prestamoejemplar_ibfk_2
    FOREIGN KEY (prestamoejemplar_ejemplar_id)
    REFERENCES biblioteca.ejemplar (ejemplar_id)
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;