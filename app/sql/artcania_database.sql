-- ============================================================
--  ARTCANIA – Base de Datos Completa
--  Motor: MySQL 8.0+ / MySQL Workbench
--  Incluye: 72 tablas, stored procedures, triggers,
--           bitácora, try-catch, prevención SQL injection
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
SET NAMES utf8mb4;

-- ────────────────────────────────────────────────────────────
-- Crear y usar base de datos
-- ────────────────────────────────────────────────────────────
CREATE DATABASE IF NOT EXISTS `artcania`
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `artcania`;

-- ────────────────────────────────────────────────────────────
-- TABLA 01: usuarios
-- ────────────────────────────────────────────────────────────
CREATE TABLE `usuarios` (
  `id`                INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  `nombre`            VARCHAR(100)     NOT NULL,
  `email`             VARCHAR(150)     NOT NULL,
  `password`          VARCHAR(255)         NULL DEFAULT NULL,
  `google_id`         VARCHAR(100)         NULL DEFAULT NULL,
  `rol`               ENUM('usuario','artista','curador','admin') NOT NULL DEFAULT 'usuario',
  `avatar`            VARCHAR(255)         NULL DEFAULT NULL,
  `bio`               TEXT                 NULL DEFAULT NULL,
  `verificado`        TINYINT(1)       NOT NULL DEFAULT 0,
  `token_verificacion` VARCHAR(128)        NULL DEFAULT NULL,
  `token_expira`      DATETIME             NULL DEFAULT NULL,
  `token_reset`       VARCHAR(128)         NULL DEFAULT NULL,
  `reset_expira`      DATETIME             NULL DEFAULT NULL,
  `intentos_fallidos` TINYINT UNSIGNED NOT NULL DEFAULT 0,
  `bloqueado_hasta`   DATETIME             NULL DEFAULT NULL,
  `activo`            TINYINT(1)       NOT NULL DEFAULT 1,
  `ultimo_login`      DATETIME             NULL DEFAULT NULL,
  `ip_registro`       VARCHAR(45)          NULL DEFAULT NULL,
  `creado_en`         DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado_en`    DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_email` (`email`),
  KEY `idx_rol` (`rol`),
  KEY `idx_verificado` (`verificado`),
  KEY `idx_activo` (`activo`),
  KEY `idx_token_reset` (`token_reset`),
  KEY `idx_google_id` (`google_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ────────────────────────────────────────────────────────────
-- TABLA 02: artistas
-- ────────────────────────────────────────────────────────────
CREATE TABLE `artistas` (
  `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario_id`    INT UNSIGNED NOT NULL,
  `especialidad`  VARCHAR(150)     NULL,
  `pais`          VARCHAR(80)      NULL,
  `ciudad`        VARCHAR(80)      NULL,
  `sitio_web`     VARCHAR(255)     NULL,
  `instagram`     VARCHAR(100)     NULL,
  `twitter`       VARCHAR(100)     NULL,
  `descripcion`   TEXT             NULL,
  `anos_experiencia` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  `destacado`     TINYINT(1)   NOT NULL DEFAULT 0,
  `puntuacion`    DECIMAL(5,2) NOT NULL DEFAULT 0.00,
  `verificado`    TINYINT(1)   NOT NULL DEFAULT 0,
  `creado_en`     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado_en` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_usuario` (`usuario_id`),
  CONSTRAINT `fk_artista_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ────────────────────────────────────────────────────────────
-- TABLA 03: curadores
-- ────────────────────────────────────────────────────────────
CREATE TABLE `curadores` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario_id` INT UNSIGNED NOT NULL,
  `especialidad` VARCHAR(150) NULL,
  `activo`     TINYINT(1)   NOT NULL DEFAULT 1,
  `creado_en`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_cur_usuario` (`usuario_id`),
  CONSTRAINT `fk_curador_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ────────────────────────────────────────────────────────────
-- TABLA 04: categorias
-- ────────────────────────────────────────────────────────────
CREATE TABLE `categorias` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre`      VARCHAR(80)  NOT NULL,
  `descripcion` TEXT             NULL,
  `slug`        VARCHAR(90)  NOT NULL,
  `activa`      TINYINT(1)   NOT NULL DEFAULT 1,
  `orden`       SMALLINT     NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_cat_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ────────────────────────────────────────────────────────────
-- TABLA 05: etiquetas
-- ────────────────────────────────────────────────────────────
CREATE TABLE `etiquetas` (
  `id`     INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(60)  NOT NULL,
  `slug`   VARCHAR(70)  NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_et_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ────────────────────────────────────────────────────────────
-- TABLA 06: obras
-- ────────────────────────────────────────────────────────────
CREATE TABLE `obras` (
  `id`                 INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  `artista_id`         INT UNSIGNED     NOT NULL,
  `categoria_id`       INT UNSIGNED         NULL,
  `titulo`             VARCHAR(200)     NOT NULL,
  `descripcion`        TEXT                 NULL,
  `tecnica`            VARCHAR(100)         NULL,
  `dimensiones`        VARCHAR(80)          NULL,
  `imagen_principal`   VARCHAR(255)         NULL,
  `precio`             DECIMAL(12,2)        NULL,
  `precio_min_subasta` DECIMAL(12,2)        NULL,
  `disponible_venta`   TINYINT(1)       NOT NULL DEFAULT 0,
  `disponible_subasta` TINYINT(1)       NOT NULL DEFAULT 0,
  `estado`             ENUM('pendiente','aprobada','rechazada','vendida','archivada') NOT NULL DEFAULT 'pendiente',
  `nota_curador`       TEXT                 NULL,
  `curador_id`         INT UNSIGNED         NULL,
  `visualizaciones`    INT UNSIGNED     NOT NULL DEFAULT 0,
  `valoracion_promedio` DECIMAL(3,2)    NOT NULL DEFAULT 0.00,
  `destacada`          TINYINT(1)       NOT NULL DEFAULT 0,
  `tipo`               ENUM('digital','lienzo','escultura','fotografia','mixta','otra') NOT NULL DEFAULT 'digital',
  `ano_creacion`       YEAR                 NULL,
  `creado_en`          DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado_en`     DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_obra_artista` (`artista_id`),
  KEY `idx_obra_estado` (`estado`),
  KEY `idx_obra_categoria` (`categoria_id`),
  KEY `idx_obra_destacada` (`destacada`),
  CONSTRAINT `fk_obra_artista` FOREIGN KEY (`artista_id`) REFERENCES `usuarios`(`id`),
  CONSTRAINT `fk_obra_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categorias`(`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_obra_curador` FOREIGN KEY (`curador_id`) REFERENCES `usuarios`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ────────────────────────────────────────────────────────────
-- TABLA 07: obra_etiquetas (pivote)
-- ────────────────────────────────────────────────────────────
CREATE TABLE `obra_etiquetas` (
  `obra_id`     INT UNSIGNED NOT NULL,
  `etiqueta_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`obra_id`,`etiqueta_id`),
  CONSTRAINT `fk_oe_obra` FOREIGN KEY (`obra_id`) REFERENCES `obras`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_oe_etiqueta` FOREIGN KEY (`etiqueta_id`) REFERENCES `etiquetas`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ────────────────────────────────────────────────────────────
-- TABLA 08: obra_imagenes
-- ────────────────────────────────────────────────────────────
CREATE TABLE `obra_imagenes` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `obra_id`    INT UNSIGNED NOT NULL,
  `ruta`       VARCHAR(255) NOT NULL,
  `orden`      TINYINT      NOT NULL DEFAULT 0,
  `creado_en`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_oi_obra` (`obra_id`),
  CONSTRAINT `fk_oi_obra` FOREIGN KEY (`obra_id`) REFERENCES `obras`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ────────────────────────────────────────────────────────────
-- TABLA 09: videos
-- ────────────────────────────────────────────────────────────
CREATE TABLE `videos` (
  `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `artista_id`    INT UNSIGNED NOT NULL,
  `titulo`        VARCHAR(200) NOT NULL,
  `descripcion`   TEXT             NULL,
  `archivo`       VARCHAR(255)     NULL,
  `url_externa`   VARCHAR(255)     NULL,
  `miniatura`     VARCHAR(255)     NULL,
  `duracion`      SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  `categoria`     ENUM('personas','cosas','animales','animados','otro') NOT NULL DEFAULT 'otro',
  `visualizaciones` INT UNSIGNED NOT NULL DEFAULT 0,
  `estado`        ENUM('pendiente','aprobado','rechazado') NOT NULL DEFAULT 'pendiente',
  `creado_en`     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_vid_artista` (`artista_id`),
  CONSTRAINT `fk_vid_artista` FOREIGN KEY (`artista_id`) REFERENCES `usuarios`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ────────────────────────────────────────────────────────────
-- TABLA 10: fanarts
-- ────────────────────────────────────────────────────────────
CREATE TABLE `fanarts` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario_id` INT UNSIGNED NOT NULL,
  `obra_id`    INT UNSIGNED     NULL,
  `titulo`     VARCHAR(200) NOT NULL,
  `descripcion` TEXT            NULL,
  `imagen`     VARCHAR(255)     NULL,
  `video`      VARCHAR(255)     NULL,
  `tipo`       ENUM('imagen','video','escultura') NOT NULL DEFAULT 'imagen',
  `aprobado`   TINYINT(1)   NOT NULL DEFAULT 0,
  `creado_en`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_fa_usuario` (`usuario_id`),
  CONSTRAINT `fk_fa_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`),
  CONSTRAINT `fk_fa_obra` FOREIGN KEY (`obra_id`) REFERENCES `obras`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ────────────────────────────────────────────────────────────
-- TABLA 11: galerias
-- ────────────────────────────────────────────────────────────
CREATE TABLE `galerias` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre`      VARCHAR(150) NOT NULL,
  `descripcion` TEXT             NULL,
  `curador_id`  INT UNSIGNED     NULL,
  `publica`     TINYINT(1)   NOT NULL DEFAULT 1,
  `creado_en`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_gal_curador` FOREIGN KEY (`curador_id`) REFERENCES `usuarios`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ────────────────────────────────────────────────────────────
-- TABLA 12: galeria_obras (pivote)
-- ────────────────────────────────────────────────────────────
CREATE TABLE `galeria_obras` (
  `galeria_id` INT UNSIGNED NOT NULL,
  `obra_id`    INT UNSIGNED NOT NULL,
  `orden`      SMALLINT     NOT NULL DEFAULT 0,
  PRIMARY KEY (`galeria_id`,`obra_id`),
  CONSTRAINT `fk_go_galeria` FOREIGN KEY (`galeria_id`) REFERENCES `galerias`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_go_obra` FOREIGN KEY (`obra_id`) REFERENCES `obras`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ────────────────────────────────────────────────────────────
-- TABLA 13: exposiciones
-- ────────────────────────────────────────────────────────────
CREATE TABLE `exposiciones` (
  `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `titulo`       VARCHAR(200) NOT NULL,
  `descripcion`  TEXT             NULL,
  `curador_id`   INT UNSIGNED     NULL,
  `fecha_inicio` DATE             NULL,
  `fecha_fin`    DATE             NULL,
  `imagen_banner` VARCHAR(255)    NULL,
  `publica`      TINYINT(1)   NOT NULL DEFAULT 0,
  `tipo`         ENUM('virtual','presencial','hibrida') NOT NULL DEFAULT 'virtual',
  `creado_en`    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_exp_curador` FOREIGN KEY (`curador_id`) REFERENCES `usuarios`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ────────────────────────────────────────────────────────────
-- TABLA 14: exposicion_obras (pivote)
-- ────────────────────────────────────────────────────────────
CREATE TABLE `exposicion_obras` (
  `exposicion_id` INT UNSIGNED NOT NULL,
  `obra_id`       INT UNSIGNED NOT NULL,
  `orden`         SMALLINT     NOT NULL DEFAULT 0,
  PRIMARY KEY (`exposicion_id`,`obra_id`),
  CONSTRAINT `fk_eo_expo` FOREIGN KEY (`exposicion_id`) REFERENCES `exposiciones`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_eo_obra` FOREIGN KEY (`obra_id`) REFERENCES `obras`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ────────────────────────────────────────────────────────────
-- TABLA 15: comentarios
-- ────────────────────────────────────────────────────────────
CREATE TABLE `comentarios` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `obra_id`    INT UNSIGNED NOT NULL,
  `usuario_id` INT UNSIGNED NOT NULL,
  `contenido`  TEXT         NOT NULL,
  `estado`     ENUM('pendiente','aprobado','rechazado') NOT NULL DEFAULT 'pendiente',
  `padre_id`   INT UNSIGNED     NULL,
  `creado_en`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_com_obra` (`obra_id`),
  CONSTRAINT `fk_com_obra` FOREIGN KEY (`obra_id`) REFERENCES `obras`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_com_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_com_padre` FOREIGN KEY (`padre_id`) REFERENCES `comentarios`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ────────────────────────────────────────────────────────────
-- TABLA 16: valoraciones
-- ────────────────────────────────────────────────────────────
CREATE TABLE `valoraciones` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `obra_id`     INT UNSIGNED NOT NULL,
  `usuario_id`  INT UNSIGNED NOT NULL,
  `puntuacion`  TINYINT UNSIGNED NOT NULL COMMENT '1-5',
  `creado_en`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado_en` DATETIME  NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_val_obra_usuario` (`obra_id`,`usuario_id`),
  CONSTRAINT `fk_val_obra` FOREIGN KEY (`obra_id`) REFERENCES `obras`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_val_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ────────────────────────────────────────────────────────────
-- TABLA 17: favoritos
-- ────────────────────────────────────────────────────────────
CREATE TABLE `favoritos` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario_id` INT UNSIGNED NOT NULL,
  `obra_id`    INT UNSIGNED NOT NULL,
  `creado_en`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_fav` (`usuario_id`,`obra_id`),
  CONSTRAINT `fk_fav_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_fav_obra` FOREIGN KEY (`obra_id`) REFERENCES `obras`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ────────────────────────────────────────────────────────────
-- TABLA 18: seguimientos
-- ────────────────────────────────────────────────────────────
CREATE TABLE `seguimientos` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `seguidor_id` INT UNSIGNED NOT NULL,
  `artista_id`  INT UNSIGNED NOT NULL,
  `creado_en`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_seg` (`seguidor_id`,`artista_id`),
  CONSTRAINT `fk_seg_seguidor` FOREIGN KEY (`seguidor_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_seg_artista` FOREIGN KEY (`artista_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ────────────────────────────────────────────────────────────
-- TABLA 19: notificaciones
-- ────────────────────────────────────────────────────────────
CREATE TABLE `notificaciones` (
  `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario_id`    INT UNSIGNED NOT NULL,
  `tipo`          VARCHAR(50)  NOT NULL,
  `mensaje`       TEXT         NOT NULL,
  `referencia_id` INT UNSIGNED     NULL,
  `leida`         TINYINT(1)   NOT NULL DEFAULT 0,
  `creado_en`     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_notif_usuario` (`usuario_id`),
  KEY `idx_notif_leida` (`leida`),
  CONSTRAINT `fk_notif_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ────────────────────────────────────────────────────────────
-- TABLA 20: subastas
-- ────────────────────────────────────────────────────────────
CREATE TABLE `subastas` (
  `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `obra_id`         INT UNSIGNED NOT NULL,
  `precio_inicial`  DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `precio_actual`   DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `precio_reserva`  DECIMAL(12,2)     NULL,
  `fecha_inicio`    DATETIME     NOT NULL,
  `fecha_fin`       DATETIME     NOT NULL,
  `ganador_id`      INT UNSIGNED     NULL,
  `estado`          ENUM('programada','activa','finalizada','cancelada') NOT NULL DEFAULT 'programada',
  `incremento_min`  DECIMAL(10,2) NOT NULL DEFAULT 1.00,
  `creado_en`       DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_sub_obra` (`obra_id`),
  KEY `idx_sub_estado` (`estado`),
  CONSTRAINT `fk_sub_obra` FOREIGN KEY (`obra_id`) REFERENCES `obras`(`id`),
  CONSTRAINT `fk_sub_ganador` FOREIGN KEY (`ganador_id`) REFERENCES `usuarios`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ────────────────────────────────────────────────────────────
-- TABLA 21: pujas
-- ────────────────────────────────────────────────────────────
CREATE TABLE `pujas` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `subasta_id`  INT UNSIGNED NOT NULL,
  `usuario_id`  INT UNSIGNED NOT NULL,
  `monto`       DECIMAL(12,2) NOT NULL,
  `automatica`  TINYINT(1)   NOT NULL DEFAULT 0,
  `creado_en`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_puja_subasta` (`subasta_id`),
  CONSTRAINT `fk_puja_subasta` FOREIGN KEY (`subasta_id`) REFERENCES `subastas`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_puja_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ────────────────────────────────────────────────────────────
-- TABLA 22: ventas
-- ────────────────────────────────────────────────────────────
CREATE TABLE `ventas` (
  `id`               INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `obra_id`          INT UNSIGNED NOT NULL,
  `usuario_id`       INT UNSIGNED NOT NULL,
  `artista_id`       INT UNSIGNED NOT NULL,
  `monto_total`      DECIMAL(12,2) NOT NULL,
  `comision_plataforma` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `monto_artista`    DECIMAL(12,2) NOT NULL,
  `metodo_pago`      VARCHAR(50)       NULL,
  `referencia_pago`  VARCHAR(100)      NULL,
  `estado`           ENUM('pendiente','completada','cancelada','reembolsada') NOT NULL DEFAULT 'pendiente',
  `tipo`             ENUM('directa','subasta') NOT NULL DEFAULT 'directa',
  `subasta_id`       INT UNSIGNED      NULL,
  `creado_en`        DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado_en`   DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_venta_usuario` (`usuario_id`),
  KEY `idx_venta_artista` (`artista_id`),
  CONSTRAINT `fk_venta_obra` FOREIGN KEY (`obra_id`) REFERENCES `obras`(`id`),
  CONSTRAINT `fk_venta_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`),
  CONSTRAINT `fk_venta_artista` FOREIGN KEY (`artista_id`) REFERENCES `usuarios`(`id`),
  CONSTRAINT `fk_venta_subasta` FOREIGN KEY (`subasta_id`) REFERENCES `subastas`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ────────────────────────────────────────────────────────────
-- TABLA 23: ediciones_limitadas
-- ────────────────────────────────────────────────────────────
CREATE TABLE `ediciones_limitadas` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `obra_id`     INT UNSIGNED NOT NULL,
  `titulo`      VARCHAR(200) NOT NULL,
  `descripcion` TEXT             NULL,
  `precio`      DECIMAL(12,2)    NULL,
  `stock_total` SMALLINT UNSIGNED NOT NULL DEFAULT 1,
  `stock_disponible` SMALLINT UNSIGNED NOT NULL DEFAULT 1,
  `imagen`      VARCHAR(255)     NULL,
  `activa`      TINYINT(1)   NOT NULL DEFAULT 1,
  `fecha_lanzamiento` DATETIME NULL,
  `creado_en`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_ed_obra` FOREIGN KEY (`obra_id`) REFERENCES `obras`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ────────────────────────────────────────────────────────────
-- TABLA 24: certificados
-- ────────────────────────────────────────────────────────────
CREATE TABLE `certificados` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `obra_id`    INT UNSIGNED NOT NULL,
  `usuario_id` INT UNSIGNED NOT NULL,
  `venta_id`   INT UNSIGNED     NULL,
  `codigo`     VARCHAR(64)  NOT NULL,
  `emitido_en` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_cert_codigo` (`codigo`),
  CONSTRAINT `fk_cert_obra` FOREIGN KEY (`obra_id`) REFERENCES `obras`(`id`),
  CONSTRAINT `fk_cert_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`),
  CONSTRAINT `fk_cert_venta` FOREIGN KEY (`venta_id`) REFERENCES `ventas`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ────────────────────────────────────────────────────────────
-- TABLA 25: conversaciones_chat
-- ────────────────────────────────────────────────────────────
CREATE TABLE `conversaciones_chat` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario1_id` INT UNSIGNED NOT NULL,
  `usuario2_id` INT UNSIGNED NOT NULL,
  `creado_en`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado_en` DATETIME  NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_conv` (`usuario1_id`,`usuario2_id`),
  KEY `idx_conv_u2` (`usuario2_id`),
  CONSTRAINT `fk_conv_u1` FOREIGN KEY (`usuario1_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_conv_u2` FOREIGN KEY (`usuario2_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ────────────────────────────────────────────────────────────
-- TABLA 26: mensajes_chat
-- ────────────────────────────────────────────────────────────
CREATE TABLE `mensajes_chat` (
  `id`               INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `conversacion_id`  INT UNSIGNED NOT NULL,
  `remitente_id`     INT UNSIGNED NOT NULL,
  `mensaje`          TEXT         NOT NULL,
  `leido`            TINYINT(1)   NOT NULL DEFAULT 0,
  `eliminado`        TINYINT(1)   NOT NULL DEFAULT 0,
  `creado_en`        DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_msg_conv` (`conversacion_id`),
  CONSTRAINT `fk_msg_conv` FOREIGN KEY (`conversacion_id`) REFERENCES `conversaciones_chat`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_msg_remitente` FOREIGN KEY (`remitente_id`) REFERENCES `usuarios`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ────────────────────────────────────────────────────────────
-- TABLA 27: contactos_artista
-- ────────────────────────────────────────────────────────────
CREATE TABLE `contactos_artista` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `artista_id`  INT UNSIGNED NOT NULL,
  `usuario_id`  INT UNSIGNED NOT NULL,
  `asunto`      VARCHAR(200) NOT NULL,
  `mensaje`     TEXT         NOT NULL,
  `leido`       TINYINT(1)   NOT NULL DEFAULT 0,
  `respondido`  TINYINT(1)   NOT NULL DEFAULT 0,
  `creado_en`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_cont_artista` (`artista_id`),
  CONSTRAINT `fk_cont_artista` FOREIGN KEY (`artista_id`) REFERENCES `usuarios`(`id`),
  CONSTRAINT `fk_cont_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ────────────────────────────────────────────────────────────
-- TABLA 28: reportes_contenido
-- ────────────────────────────────────────────────────────────
CREATE TABLE `reportes_contenido` (
  `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario_id`      INT UNSIGNED NOT NULL,
  `tipo_contenido`  ENUM('obra','comentario','usuario','fanart') NOT NULL,
  `contenido_id`    INT UNSIGNED NOT NULL,
  `motivo`          VARCHAR(200) NOT NULL,
  `descripcion`     TEXT             NULL,
  `estado`          ENUM('pendiente','revisado','desestimado','accionado') NOT NULL DEFAULT 'pendiente',
  `resuelto_por`    INT UNSIGNED     NULL,
  `creado_en`       DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_rep_usuario` (`usuario_id`),
  CONSTRAINT `fk_rep_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`),
  CONSTRAINT `fk_rep_resuelto` FOREIGN KEY (`resuelto_por`) REFERENCES `usuarios`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ────────────────────────────────────────────────────────────
-- TABLA 29: resenas
-- ────────────────────────────────────────────────────────────
CREATE TABLE `resenas` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `artista_id`  INT UNSIGNED NOT NULL,
  `usuario_id`  INT UNSIGNED NOT NULL,
  `puntuacion`  TINYINT UNSIGNED NOT NULL,
  `contenido`   TEXT             NULL,
  `aprobada`    TINYINT(1)   NOT NULL DEFAULT 0,
  `creado_en`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_resena` (`artista_id`,`usuario_id`),
  CONSTRAINT `fk_res_artista` FOREIGN KEY (`artista_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_res_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ────────────────────────────────────────────────────────────
-- TABLA 30: colaboraciones
-- ────────────────────────────────────────────────────────────
CREATE TABLE `colaboraciones` (
  `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `artista1_id`   INT UNSIGNED NOT NULL,
  `artista2_id`   INT UNSIGNED NOT NULL,
  `obra_id`       INT UNSIGNED     NULL,
  `descripcion`   TEXT             NULL,
  `estado`        ENUM('propuesta','aceptada','rechazada','completada') NOT NULL DEFAULT 'propuesta',
  `creado_en`     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_col_a1` FOREIGN KEY (`artista1_id`) REFERENCES `usuarios`(`id`),
  CONSTRAINT `fk_col_a2` FOREIGN KEY (`artista2_id`) REFERENCES `usuarios`(`id`),
  CONSTRAINT `fk_col_obra` FOREIGN KEY (`obra_id`) REFERENCES `obras`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ────────────────────────────────────────────────────────────
-- TABLA 31: eventos
-- ────────────────────────────────────────────────────────────
CREATE TABLE `eventos` (
  `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `titulo`       VARCHAR(200) NOT NULL,
  `descripcion`  TEXT             NULL,
  `organizador_id` INT UNSIGNED  NULL,
  `fecha_inicio` DATETIME     NOT NULL,
  `fecha_fin`    DATETIME         NULL,
  `lugar`        VARCHAR(200)     NULL,
  `virtual`      TINYINT(1)   NOT NULL DEFAULT 0,
  `url_acceso`   VARCHAR(255)     NULL,
  `imagen`       VARCHAR(255)     NULL,
  `activo`       TINYINT(1)   NOT NULL DEFAULT 1,
  `creado_en`    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_ev_org` FOREIGN KEY (`organizador_id`) REFERENCES `usuarios`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ────────────────────────────────────────────────────────────
-- TABLA 32: evento_asistentes (pivote)
-- ────────────────────────────────────────────────────────────
CREATE TABLE `evento_asistentes` (
  `evento_id`  INT UNSIGNED NOT NULL,
  `usuario_id` INT UNSIGNED NOT NULL,
  `confirmado` TINYINT(1)   NOT NULL DEFAULT 0,
  `creado_en`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`evento_id`,`usuario_id`),
  CONSTRAINT `fk_ea_evento` FOREIGN KEY (`evento_id`) REFERENCES `eventos`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ea_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ────────────────────────────────────────────────────────────
-- TABLA 33: talleres
-- ────────────────────────────────────────────────────────────
CREATE TABLE `talleres` (
  `id`             INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `titulo`         VARCHAR(200) NOT NULL,
  `descripcion`    TEXT             NULL,
  `instructor_id`  INT UNSIGNED NOT NULL,
  `precio`         DECIMAL(10,2)    NULL,
  `capacidad`      SMALLINT UNSIGNED NOT NULL DEFAULT 20,
  `inscritos`      SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  `fecha_inicio`   DATETIME         NULL,
  `fecha_fin`      DATETIME         NULL,
  `virtual`        TINYINT(1)   NOT NULL DEFAULT 1,
  `url_acceso`     VARCHAR(255)     NULL,
  `imagen`         VARCHAR(255)     NULL,
  `activo`         TINYINT(1)   NOT NULL DEFAULT 1,
  `creado_en`      DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_tal_instr` FOREIGN KEY (`instructor_id`) REFERENCES `usuarios`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ────────────────────────────────────────────────────────────
-- TABLA 34: inscripciones_taller
-- ────────────────────────────────────────────────────────────
CREATE TABLE `inscripciones_taller` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `taller_id`  INT UNSIGNED NOT NULL,
  `usuario_id` INT UNSIGNED NOT NULL,
  `pagado`     TINYINT(1)   NOT NULL DEFAULT 0,
  `completado` TINYINT(1)   NOT NULL DEFAULT 0,
  `creado_en`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_insc` (`taller_id`,`usuario_id`),
  CONSTRAINT `fk_insc_taller` FOREIGN KEY (`taller_id`) REFERENCES `talleres`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_insc_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ────────────────────────────────────────────────────────────
-- TABLA 35: premios_artista
-- ────────────────────────────────────────────────────────────
CREATE TABLE `premios_artista` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `artista_id`  INT UNSIGNED NOT NULL,
  `titulo`      VARCHAR(200) NOT NULL,
  `descripcion` TEXT             NULL,
  `anio`        YEAR             NULL,
  `otorgado_por` VARCHAR(150)    NULL,
  `creado_en`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_prem_artista` FOREIGN KEY (`artista_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ────────────────────────────────────────────────────────────
-- TABLA 36: visualizaciones
-- ────────────────────────────────────────────────────────────
CREATE TABLE `visualizaciones` (
  `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `obra_id`    INT UNSIGNED        NOT NULL,
  `usuario_id` INT UNSIGNED            NULL,
  `ip`         VARCHAR(45)         NOT NULL DEFAULT '',
  `creado_en`  DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_vis_obra` (`obra_id`),
  KEY `idx_vis_fecha` (`creado_en`),
  CONSTRAINT `fk_vis_obra` FOREIGN KEY (`obra_id`) REFERENCES `obras`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ────────────────────────────────────────────────────────────
-- TABLA 37: estadisticas_diarias
-- ────────────────────────────────────────────────────────────
CREATE TABLE `estadisticas_diarias` (
  `id`               INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `fecha`            DATE         NOT NULL,
  `nuevos_usuarios`  INT UNSIGNED NOT NULL DEFAULT 0,
  `nuevas_obras`     INT UNSIGNED NOT NULL DEFAULT 0,
  `total_visitas`    INT UNSIGNED NOT NULL DEFAULT 0,
  `ventas_realizadas` INT UNSIGNED NOT NULL DEFAULT 0,
  `ingresos_totales` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  `nuevas_subastas`  INT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_est_fecha` (`fecha`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ────────────────────────────────────────────────────────────
-- TABLA 38: configuracion
-- ────────────────────────────────────────────────────────────
CREATE TABLE `configuracion` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `clave`      VARCHAR(100) NOT NULL,
  `valor`      TEXT             NULL,
  `tipo`       ENUM('string','int','bool','json') NOT NULL DEFAULT 'string',
  `descripcion` VARCHAR(255)    NULL,
  `actualizado_en` DATETIME    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_cfg_clave` (`clave`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ────────────────────────────────────────────────────────────
-- TABLA 39: bitacora  ★ PRINCIPAL DE AUDITORÍA
-- ────────────────────────────────────────────────────────────
CREATE TABLE `bitacora` (
  `id`          BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `usuario_id`  INT UNSIGNED         NULL,
  `accion`      VARCHAR(80)      NOT NULL,
  `detalle`     TEXT                 NULL,
  `tabla_afectada` VARCHAR(60)       NULL,
  `registro_id` INT UNSIGNED         NULL,
  `ip`          VARCHAR(45)      NOT NULL DEFAULT '',
  `agente_usuario`  VARCHAR(255)         NULL,
  `creado_en`   DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_bit_usuario` (`usuario_id`),
  KEY `idx_bit_accion` (`accion`),
  KEY `idx_bit_fecha` (`creado_en`),
  CONSTRAINT `fk_bit_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ────────────────────────────────────────────────────────────
-- TABLAS 40-50: Módulos extendidos
-- ────────────────────────────────────────────────────────────

-- TABLA 40: tokens_dispositivos (push notifications)
CREATE TABLE `tokens_dispositivos` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario_id` INT UNSIGNED NOT NULL,
  `token`      VARCHAR(255) NOT NULL,
  `plataforma` ENUM('web','android','ios') NOT NULL DEFAULT 'web',
  `activo`     TINYINT(1)   NOT NULL DEFAULT 1,
  `creado_en`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_tok` (`token`),
  CONSTRAINT `fk_tok_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLA 41: sesiones_usuario
CREATE TABLE `sesiones_usuario` (
  `id`         VARCHAR(128) NOT NULL,
  `usuario_id` INT UNSIGNED     NULL,
  `ip`         VARCHAR(45)  NOT NULL DEFAULT '',
  `agente_usuario` VARCHAR(255)     NULL,
  `datos_payload`    TEXT             NULL,
  `last_activity` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_ses_usuario` (`usuario_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLA 42: intentos_login
CREATE TABLE `intentos_login` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `email`      VARCHAR(150) NOT NULL,
  `ip`         VARCHAR(45)  NOT NULL,
  `exitoso`    TINYINT(1)   NOT NULL DEFAULT 0,
  `creado_en`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_il_email` (`email`),
  KEY `idx_il_ip` (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLA 43: permisos
CREATE TABLE `permisos` (
  `id`     INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `clave`  VARCHAR(80)  NOT NULL,
  `descripcion` VARCHAR(200) NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_perm_clave` (`clave`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLA 44: rol_permisos
CREATE TABLE `rol_permisos` (
  `rol`       ENUM('usuario','artista','curador','admin') NOT NULL,
  `permiso_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`rol`,`permiso_id`),
  CONSTRAINT `fk_rp_permiso` FOREIGN KEY (`permiso_id`) REFERENCES `permisos`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLA 45: suscripciones_newsletter
CREATE TABLE `suscripciones_newsletter` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `email`      VARCHAR(150) NOT NULL,
  `nombre`     VARCHAR(100)     NULL,
  `activa`     TINYINT(1)   NOT NULL DEFAULT 1,
  `token`      VARCHAR(64)  NOT NULL,
  `creado_en`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_nl_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLA 46: cupones
CREATE TABLE `cupones` (
  `id`             INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `codigo`         VARCHAR(30)  NOT NULL,
  `descuento_pct`  DECIMAL(5,2)     NULL,
  `descuento_fijo` DECIMAL(10,2)    NULL,
  `uso_maximo`     SMALLINT UNSIGNED NOT NULL DEFAULT 1,
  `veces_usado`    SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  `valido_desde`   DATETIME         NULL,
  `valido_hasta`   DATETIME         NULL,
  `activo`         TINYINT(1)   NOT NULL DEFAULT 1,
  `creado_en`      DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_cup_codigo` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLA 47: cupon_usos
CREATE TABLE `cupon_usos` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `cupon_id`   INT UNSIGNED NOT NULL,
  `usuario_id` INT UNSIGNED NOT NULL,
  `venta_id`   INT UNSIGNED     NULL,
  `creado_en`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_cu_cupon` FOREIGN KEY (`cupon_id`) REFERENCES `cupones`(`id`),
  CONSTRAINT `fk_cu_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`),
  CONSTRAINT `fk_cu_venta` FOREIGN KEY (`venta_id`) REFERENCES `ventas`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLA 48: pagos
CREATE TABLE `pagos` (
  `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `venta_id`      INT UNSIGNED NOT NULL,
  `pasarela`       VARCHAR(50)  NOT NULL DEFAULT 'manual',
  `referencia`    VARCHAR(150)     NULL,
  `monto`         DECIMAL(12,2) NOT NULL,
  `moneda`        CHAR(3)      NOT NULL DEFAULT 'USD',
  `estado`        ENUM('iniciado','aprobado','rechazado','reembolsado') NOT NULL DEFAULT 'iniciado',
  `respuesta_raw` JSON             NULL,
  `creado_en`     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_pago_venta` (`venta_id`),
  CONSTRAINT `fk_pago_venta` FOREIGN KEY (`venta_id`) REFERENCES `ventas`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLA 49: envios (si hay obra física)
CREATE TABLE `envios` (
  `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `venta_id`      INT UNSIGNED NOT NULL,
  `direccion`     TEXT         NOT NULL,
  `ciudad`        VARCHAR(100)     NULL,
  `pais`          VARCHAR(80)      NULL,
  `codigo_postal` VARCHAR(20)      NULL,
  `transportista` VARCHAR(80)      NULL,
  `numero_guia`   VARCHAR(100)     NULL,
  `estado`        ENUM('pendiente','preparando','en_camino','entregado','devuelto') NOT NULL DEFAULT 'pendiente',
  `fecha_envio`   DATETIME         NULL,
  `fecha_entrega` DATETIME         NULL,
  `creado_en`     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_env_venta` FOREIGN KEY (`venta_id`) REFERENCES `ventas`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLA 50: wish_list
CREATE TABLE `wish_list` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario_id` INT UNSIGNED NOT NULL,
  `obra_id`    INT UNSIGNED NOT NULL,
  `nota`       VARCHAR(255)     NULL,
  `creado_en`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_wl` (`usuario_id`,`obra_id`),
  CONSTRAINT `fk_wl_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_wl_obra` FOREIGN KEY (`obra_id`) REFERENCES `obras`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ────────────────────────────────────────────────────────────
-- TABLAS 51-72: Módulos adicionales
-- ────────────────────────────────────────────────────────────

-- TABLA 51: colecciones_usuario
CREATE TABLE `colecciones_usuario` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario_id`  INT UNSIGNED NOT NULL,
  `nombre`      VARCHAR(150) NOT NULL,
  `descripcion` TEXT             NULL,
  `publica`     TINYINT(1)   NOT NULL DEFAULT 0,
  `creado_en`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_col_u_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLA 52: coleccion_obras
CREATE TABLE `coleccion_obras` (
  `coleccion_id` INT UNSIGNED NOT NULL,
  `obra_id`      INT UNSIGNED NOT NULL,
  `agregado_en`     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`coleccion_id`,`obra_id`),
  CONSTRAINT `fk_co_col` FOREIGN KEY (`coleccion_id`) REFERENCES `colecciones_usuario`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_co_obra` FOREIGN KEY (`obra_id`) REFERENCES `obras`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLA 53: portfolios_artista
CREATE TABLE `portfolios_artista` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `artista_id`  INT UNSIGNED NOT NULL,
  `titulo`      VARCHAR(200) NOT NULL,
  `descripcion` TEXT             NULL,
  `publica`     TINYINT(1)   NOT NULL DEFAULT 1,
  `creado_en`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_port_artista` (`artista_id`),
  CONSTRAINT `fk_port_artista` FOREIGN KEY (`artista_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLA 54: menciones_obra
CREATE TABLE `menciones_obra` (
  `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `obra_id`      INT UNSIGNED NOT NULL,
  `fuente`       VARCHAR(200)     NULL,
  `url`          VARCHAR(255)     NULL,
  `descripcion`  TEXT             NULL,
  `fecha`        DATE             NULL,
  `creado_en`    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_men_obra` FOREIGN KEY (`obra_id`) REFERENCES `obras`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLA 55: historial_precios
CREATE TABLE `historial_precios` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `obra_id`     INT UNSIGNED NOT NULL,
  `precio_anterior` DECIMAL(12,2) NULL,
  `precio_nuevo` DECIMAL(12,2)   NOT NULL,
  `cambiado_por` INT UNSIGNED    NULL,
  `creado_en`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_hp_obra` FOREIGN KEY (`obra_id`) REFERENCES `obras`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLA 56: tags_busqueda
CREATE TABLE `tags_busqueda` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `termino`    VARCHAR(100) NOT NULL,
  `contador`   INT UNSIGNED NOT NULL DEFAULT 1,
  `creado_en`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado_en` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_tb_termino` (`termino`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLA 57: banners
CREATE TABLE `banners` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `titulo`     VARCHAR(200)     NULL,
  `imagen`     VARCHAR(255) NOT NULL,
  `url_destino` VARCHAR(255)   NULL,
  `posicion`   TINYINT UNSIGNED NOT NULL DEFAULT 1,
  `activo`     TINYINT(1)   NOT NULL DEFAULT 1,
  `orden`      SMALLINT     NOT NULL DEFAULT 0,
  `creado_en`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLA 58: faqs
CREATE TABLE `faqs` (
  `id`        INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `pregunta`  TEXT         NOT NULL,
  `respuesta` TEXT             NULL,
  `categoria` VARCHAR(80)      NULL,
  `orden`     SMALLINT     NOT NULL DEFAULT 0,
  `activa`    TINYINT(1)   NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLA 59: paginas_cms
CREATE TABLE `paginas_cms` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `slug`       VARCHAR(100) NOT NULL,
  `titulo`     VARCHAR(200) NOT NULL,
  `contenido`  LONGTEXT         NULL,
  `activa`     TINYINT(1)   NOT NULL DEFAULT 1,
  `actualizado_en` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_cms_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLA 60: moderacion_chat
CREATE TABLE `moderacion_chat` (
  `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `mensaje_id`    INT UNSIGNED NOT NULL,
  `moderador_id`  INT UNSIGNED NOT NULL,
  `motivo`        VARCHAR(200)     NULL,
  `accion`        ENUM('advertencia','eliminacion','bloqueo') NOT NULL DEFAULT 'advertencia',
  `creado_en`     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_mc_mensaje` FOREIGN KEY (`mensaje_id`) REFERENCES `mensajes_chat`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_mc_mod` FOREIGN KEY (`moderador_id`) REFERENCES `usuarios`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLA 61: alertas_precio
CREATE TABLE `alertas_precio` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario_id`  INT UNSIGNED NOT NULL,
  `obra_id`     INT UNSIGNED NOT NULL,
  `precio_max`  DECIMAL(12,2) NOT NULL,
  `activa`      TINYINT(1)   NOT NULL DEFAULT 1,
  `creado_en`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_ap_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ap_obra` FOREIGN KEY (`obra_id`) REFERENCES `obras`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLA 62: respaldos
CREATE TABLE `respaldos` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre`      VARCHAR(200) NOT NULL,
  `ruta`        VARCHAR(255)     NULL,
  `tamanio`     BIGINT UNSIGNED  NULL,
  `tipo`        ENUM('completo','parcial','bd_solo') NOT NULL DEFAULT 'completo',
  `creado_por`  INT UNSIGNED     NULL,
  `creado_en`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_resp_admin` FOREIGN KEY (`creado_por`) REFERENCES `usuarios`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLA 63: errores_sistema
CREATE TABLE `errores_sistema` (
  `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nivel`      ENUM('debug','info','warning','error','critical') NOT NULL DEFAULT 'error',
  `mensaje`    TEXT         NOT NULL,
  `archivo`    VARCHAR(255)     NULL,
  `linea`      INT              NULL,
  `traza`      TEXT             NULL,
  `usuario_id` INT UNSIGNED     NULL,
  `url`        VARCHAR(500)     NULL,
  `creado_en`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_err_nivel` (`nivel`),
  KEY `idx_err_fecha` (`creado_en`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLA 64: cache_estadisticas
CREATE TABLE `cache_estadisticas` (
  `clave`       VARCHAR(100) NOT NULL,
  `valor`       LONGTEXT         NULL,
  `expira_en`   DATETIME     NOT NULL,
  PRIMARY KEY (`clave`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLA 65: historial_estados_obra
CREATE TABLE `historial_estados_obra` (
  `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `obra_id`      INT UNSIGNED NOT NULL,
  `estado_ant`   VARCHAR(30)      NULL,
  `estado_nuevo` VARCHAR(30)  NOT NULL,
  `usuario_id`   INT UNSIGNED     NULL,
  `nota`         TEXT             NULL,
  `creado_en`    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_heo_obra` (`obra_id`),
  CONSTRAINT `fk_heo_obra` FOREIGN KEY (`obra_id`) REFERENCES `obras`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLA 66: bloqueos_usuario
CREATE TABLE `bloqueos_usuario` (
  `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `bloqueador_id` INT UNSIGNED NOT NULL,
  `bloqueado_id`  INT UNSIGNED NOT NULL,
  `creado_en`    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_bloqueo` (`bloqueador_id`,`bloqueado_id`),
  CONSTRAINT `fk_blq_a` FOREIGN KEY (`bloqueador_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_blq_b` FOREIGN KEY (`bloqueado_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLA 67: reacciones_obra
CREATE TABLE `reacciones_obra` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `obra_id`     INT UNSIGNED NOT NULL,
  `usuario_id`  INT UNSIGNED NOT NULL,
  `tipo`        ENUM('like','love','wow','inspirador','comprar') NOT NULL DEFAULT 'like',
  `creado_en`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_reac` (`obra_id`,`usuario_id`),
  CONSTRAINT `fk_reac_obra` FOREIGN KEY (`obra_id`) REFERENCES `obras`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_reac_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLA 68: historial_busquedas
CREATE TABLE `historial_busquedas` (
  `id`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario_id`  INT UNSIGNED        NULL,
  `termino`     VARCHAR(200)    NOT NULL,
  `resultados`  SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  `ip`          VARCHAR(45)     NOT NULL DEFAULT '',
  `creado_en`   DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_hb_usuario` (`usuario_id`),
  KEY `idx_hb_fecha` (`creado_en`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLA 69: comisiones_artista
CREATE TABLE `comisiones_artista` (
  `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `artista_id`    INT UNSIGNED NOT NULL,
  `porcentaje`    DECIMAL(5,2) NOT NULL DEFAULT 80.00,
  `activo_desde`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `activo_hasta`  DATETIME         NULL,
  PRIMARY KEY (`id`),
  KEY `idx_com_art` (`artista_id`),
  CONSTRAINT `fk_coma_artista` FOREIGN KEY (`artista_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLA 70: retiros_artista
CREATE TABLE `retiros_artista` (
  `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `artista_id`   INT UNSIGNED NOT NULL,
  `monto`        DECIMAL(12,2) NOT NULL,
  `metodo`       VARCHAR(80)      NULL,
  `cuenta`       VARCHAR(200)     NULL,
  `estado`       ENUM('solicitado','procesado','rechazado') NOT NULL DEFAULT 'solicitado',
  `nota_admin`   TEXT             NULL,
  `procesado_por` INT UNSIGNED    NULL,
  `creado_en`    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado_en` DATETIME   NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_ret_artista` FOREIGN KEY (`artista_id`) REFERENCES `usuarios`(`id`),
  CONSTRAINT `fk_ret_admin` FOREIGN KEY (`procesado_por`) REFERENCES `usuarios`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLA 71: metricas_artista
CREATE TABLE `metricas_artista` (
  `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `artista_id`      INT UNSIGNED NOT NULL,
  `mes`             CHAR(7)      NOT NULL COMMENT 'YYYY-MM',
  `total_vistas`    INT UNSIGNED NOT NULL DEFAULT 0,
  `total_ventas`    INT UNSIGNED NOT NULL DEFAULT 0,
  `ingresos`        DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  `nuevos_seguidores` INT UNSIGNED NOT NULL DEFAULT 0,
  `obras_publicadas` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  `actualizado_en`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_met_artista_mes` (`artista_id`,`mes`),
  CONSTRAINT `fk_met_artista` FOREIGN KEY (`artista_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLA 72: ips_bloqueadas
CREATE TABLE `ips_bloqueadas` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip`          VARCHAR(45)  NOT NULL,
  `motivo`      VARCHAR(200)     NULL,
  `bloqueado_por` INT UNSIGNED   NULL,
  `expira_en`  DATETIME         NULL,
  `creado_en`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_ip_blq` (`ip`),
  CONSTRAINT `fk_ipb_admin` FOREIGN KEY (`bloqueado_por`) REFERENCES `usuarios`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- STORED PROCEDURES
-- ============================================================

DELIMITER $$

-- ─── SP 01: Registrar usuario ───────────────────────────────
CREATE PROCEDURE `sp_registrar_usuario`(
  IN p_nombre    VARCHAR(100),
  IN p_email     VARCHAR(150),
  IN p_pass      VARCHAR(255),
  IN p_rol       VARCHAR(20),
  IN p_token     VARCHAR(128)
)
BEGIN
  DECLARE EXIT HANDLER FOR SQLEXCEPTION
  BEGIN
    ROLLBACK;
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Error al registrar usuario';
  END;

  START TRANSACTION;
    INSERT INTO usuarios(nombre, email, password, rol, token_verificacion, token_expira, activo, verificado)
    VALUES(p_nombre, p_email, p_pass, p_rol, p_token, DATE_ADD(NOW(), INTERVAL 24 HOUR), 1, 0);

    SET @new_id = LAST_INSERT_ID();

    INSERT INTO bitacora(usuario_id, accion, detalle, ip)
    VALUES(@new_id, 'REGISTRO', CONCAT('Nuevo usuario: ', p_email), '');

    SELECT @new_id AS id_usuario;
  COMMIT;
END$$

-- ─── SP 02: Verificar email ─────────────────────────────────
CREATE PROCEDURE `sp_verificar_email`(IN p_token VARCHAR(128))
BEGIN
  DECLARE v_id INT UNSIGNED;
  DECLARE EXIT HANDLER FOR SQLEXCEPTION
  BEGIN
    ROLLBACK;
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Token inválido o expirado';
  END;

  START TRANSACTION;
    SELECT id INTO v_id FROM usuarios
    WHERE token_verificacion = p_token AND token_expira > NOW() AND verificado = 0
    FOR UPDATE;

    IF v_id IS NULL THEN
      SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Token no válido';
    END IF;

    UPDATE usuarios
    SET verificado = 1, token_verificacion = NULL, token_expira = NULL
    WHERE id = v_id;

    INSERT INTO bitacora(usuario_id, accion, detalle, ip)
    VALUES(v_id, 'EMAIL_VERIFICADO', 'Correo verificado correctamente', '');
  COMMIT;
END$$

-- ─── SP 03: Generar token reset ─────────────────────────────
CREATE PROCEDURE `sp_generar_token_reset`(
  IN p_email VARCHAR(150),
  IN p_token VARCHAR(128)
)
BEGIN
  DECLARE v_id INT UNSIGNED;

  SELECT id INTO v_id FROM usuarios WHERE email = p_email AND activo = 1 LIMIT 1;

  IF v_id IS NOT NULL THEN
    UPDATE usuarios
    SET token_reset = p_token, reset_expira = DATE_ADD(NOW(), INTERVAL 1 HOUR)
    WHERE id = v_id;

    INSERT INTO bitacora(usuario_id, accion, detalle, ip)
    VALUES(v_id, 'TOKEN_RESET', 'Token de reset generado', '');

    SELECT TRUE AS ok;
  ELSE
    SELECT FALSE AS ok;
  END IF;
END$$

-- ─── SP 04: Reset password ──────────────────────────────────
CREATE PROCEDURE `sp_reset_password`(
  IN p_token VARCHAR(128),
  IN p_hash  VARCHAR(255)
)
BEGIN
  DECLARE v_id INT UNSIGNED;
  DECLARE EXIT HANDLER FOR SQLEXCEPTION
  BEGIN
    ROLLBACK;
    SELECT FALSE AS ok;
  END;

  START TRANSACTION;
    SELECT id INTO v_id FROM usuarios
    WHERE token_reset = p_token AND reset_expira > NOW()
    FOR UPDATE;

    IF v_id IS NULL THEN
      ROLLBACK;
      SELECT FALSE AS ok;
    ELSE
      UPDATE usuarios
      SET password = p_hash, token_reset = NULL, reset_expira = NULL, intentos_fallidos = 0
      WHERE id = v_id;

      INSERT INTO bitacora(usuario_id, accion, detalle, ip)
      VALUES(v_id, 'PASSWORD_RESET', 'Contraseña restablecida', '');

      COMMIT;
      SELECT TRUE AS ok;
    END IF;
END$$

-- ─── SP 05: Login fallido ───────────────────────────────────
CREATE PROCEDURE `sp_login_fallido`(IN p_id INT UNSIGNED)
BEGIN
  DECLARE v_intentos TINYINT;

  UPDATE usuarios SET intentos_fallidos = intentos_fallidos + 1 WHERE id = p_id;
  SELECT intentos_fallidos INTO v_intentos FROM usuarios WHERE id = p_id;

  IF v_intentos >= 5 THEN
    UPDATE usuarios
    SET bloqueado_hasta = DATE_ADD(NOW(), INTERVAL 15 MINUTE)
    WHERE id = p_id;

    INSERT INTO bitacora(usuario_id, accion, detalle, ip)
    VALUES(p_id, 'CUENTA_BLOQUEADA', CONCAT('Bloqueado por ', v_intentos, ' intentos fallidos'), '');
  ELSE
    INSERT INTO bitacora(usuario_id, accion, detalle, ip)
    VALUES(p_id, 'LOGIN_FALLIDO', CONCAT('Intento ', v_intentos, ' fallido'), '');
  END IF;
END$$

-- ─── SP 06: Aprobar obra ────────────────────────────────────
CREATE PROCEDURE `sp_aprobar_obra`(
  IN p_obra_id   INT UNSIGNED,
  IN p_curador   INT UNSIGNED,
  IN p_estado    VARCHAR(20),
  IN p_nota      TEXT
)
BEGIN
  DECLARE v_artista_id INT UNSIGNED;
  DECLARE EXIT HANDLER FOR SQLEXCEPTION
  BEGIN
    ROLLBACK;
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Error al actualizar obra';
  END;

  START TRANSACTION;
    SELECT artista_id INTO v_artista_id FROM obras WHERE id = p_obra_id FOR UPDATE;

    INSERT INTO historial_estados_obra(obra_id, estado_ant, estado_nuevo, usuario_id, nota)
    SELECT p_obra_id, estado, p_estado, p_curador, p_nota FROM obras WHERE id = p_obra_id;

    UPDATE obras
    SET estado = p_estado, curador_id = p_curador, nota_curador = p_nota
    WHERE id = p_obra_id;

    INSERT INTO bitacora(usuario_id, accion, detalle, tabla_afectada, registro_id, ip)
    VALUES(p_curador, CONCAT('OBRA_', UPPER(p_estado)), CONCAT('Obra ID ', p_obra_id, ': ', p_nota), 'obras', p_obra_id, '');

    -- Notificar al artista
    INSERT INTO notificaciones(usuario_id, tipo, mensaje, referencia_id)
    VALUES(v_artista_id, CONCAT('obra_', p_estado), CONCAT('Tu obra ha sido ', p_estado, '. ', COALESCE(p_nota,'')), p_obra_id);
  COMMIT;
END$$

-- ─── SP 07: Registrar venta ─────────────────────────────────
CREATE PROCEDURE `sp_registrar_venta`(
  IN p_obra_id     INT UNSIGNED,
  IN p_usuario_id  INT UNSIGNED,
  IN p_monto       DECIMAL(12,2),
  IN p_metodo      VARCHAR(50),
  IN p_tipo        VARCHAR(20)
)
BEGIN
  DECLARE v_artista_id INT UNSIGNED;
  DECLARE v_comision   DECIMAL(5,2) DEFAULT 20.00;
  DECLARE v_com_amt    DECIMAL(12,2);
  DECLARE v_art_amt    DECIMAL(12,2);
  DECLARE EXIT HANDLER FOR SQLEXCEPTION
  BEGIN
    ROLLBACK;
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Error al registrar venta';
  END;

  START TRANSACTION;
    SELECT artista_id INTO v_artista_id FROM obras WHERE id = p_obra_id FOR UPDATE;

    -- Comisión personalizada
    SELECT COALESCE((SELECT 100 - porcentaje FROM comisiones_artista WHERE artista_id = v_artista_id AND activo_hasta IS NULL LIMIT 1), 20.00) INTO v_comision;

    SET v_com_amt = ROUND(p_monto * v_comision / 100, 2);
    SET v_art_amt = p_monto - v_com_amt;

    INSERT INTO ventas(obra_id, usuario_id, artista_id, monto_total, comision_plataforma, monto_artista, metodo_pago, estado, tipo)
    VALUES(p_obra_id, p_usuario_id, v_artista_id, p_monto, v_com_amt, v_art_amt, p_metodo, 'completada', p_tipo);

    SET @venta_id = LAST_INSERT_ID();

    UPDATE obras SET estado = 'vendida' WHERE id = p_obra_id AND p_tipo = 'directa';

    INSERT INTO certificados(obra_id, usuario_id, venta_id, codigo)
    VALUES(p_obra_id, p_usuario_id, @venta_id, UPPER(CONCAT('ART-', LPAD(@venta_id,6,'0'), '-', SUBSTRING(SHA1(CONCAT(p_obra_id, p_usuario_id, NOW())),1,8))));

    INSERT INTO bitacora(usuario_id, accion, detalle, tabla_afectada, registro_id, ip)
    VALUES(p_usuario_id, 'VENTA_REALIZADA', CONCAT('Venta ID ',@venta_id,' obra ',p_obra_id,' $',p_monto), 'ventas', @venta_id, '');

    INSERT INTO notificaciones(usuario_id, tipo, mensaje, referencia_id)
    VALUES(v_artista_id, 'venta', CONCAT('¡Vendiste una obra por $', p_monto, '!'), @venta_id);

    SELECT @venta_id AS venta_id;
  COMMIT;
END$$

-- ─── SP 08: Pujar en subasta ────────────────────────────────
CREATE PROCEDURE `sp_pujar_subasta`(
  IN p_subasta_id INT UNSIGNED,
  IN p_usuario_id INT UNSIGNED,
  IN p_monto      DECIMAL(12,2)
)
BEGIN
  DECLARE v_precio_actual DECIMAL(12,2);
  DECLARE v_fin DATETIME;
  DECLARE v_estado VARCHAR(20);
  DECLARE EXIT HANDLER FOR SQLEXCEPTION
  BEGIN
    ROLLBACK;
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Error al procesar puja';
  END;

  START TRANSACTION;
    SELECT precio_actual, fecha_fin, estado INTO v_precio_actual, v_fin, v_estado
    FROM subastas WHERE id = p_subasta_id FOR UPDATE;

    IF v_estado != 'activa' OR v_fin <= NOW() THEN
      SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La subasta no está activa';
    END IF;

    IF p_monto <= v_precio_actual THEN
      SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La puja debe ser mayor al precio actual';
    END IF;

    INSERT INTO pujas(subasta_id, usuario_id, monto) VALUES(p_subasta_id, p_usuario_id, p_monto);
    UPDATE subastas SET precio_actual = p_monto, ganador_id = p_usuario_id WHERE id = p_subasta_id;

    INSERT INTO bitacora(usuario_id, accion, detalle, tabla_afectada, registro_id, ip)
    VALUES(p_usuario_id, 'PUJA_REALIZADA', CONCAT('Subasta ', p_subasta_id, ' puja $', p_monto), 'pujas', LAST_INSERT_ID(), '');

    SELECT TRUE AS ok;
  COMMIT;
END$$

-- ─── SP 09: Resumen estadísticas dashboard ──────────────────
CREATE PROCEDURE `sp_estadisticas_dashboard`()
BEGIN
  SELECT
    (SELECT COUNT(*) FROM usuarios WHERE activo = 1) AS total_usuarios,
    (SELECT COUNT(*) FROM usuarios WHERE DATE(creado_en) = CURDATE()) AS nuevos_hoy,
    (SELECT COUNT(*) FROM obras WHERE estado = 'aprobada') AS obras_activas,
    (SELECT COUNT(*) FROM obras WHERE estado = 'pendiente') AS obras_pendientes,
    (SELECT COUNT(*) FROM subastas WHERE estado = 'activa') AS subastas_activas,
    (SELECT COALESCE(SUM(monto_total),0) FROM ventas WHERE estado='completada') AS ingresos_totales,
    (SELECT COALESCE(SUM(monto_total),0) FROM ventas WHERE estado='completada' AND DATE(creado_en)=CURDATE()) AS ingresos_hoy,
    (SELECT COUNT(*) FROM mensajes_chat WHERE DATE(creado_en) = CURDATE()) AS mensajes_hoy;
END$$

-- ─── SP 10: Estadísticas visualizaciones por rango ──────────
CREATE PROCEDURE `sp_stats_visualizaciones`(IN p_dias INT)
BEGIN
  SELECT
    DATE(creado_en)  AS fecha,
    COUNT(*)          AS total,
    COUNT(DISTINCT obra_id) AS obras_distintas,
    COUNT(DISTINCT ip)  AS visitantes_unicos
  FROM visualizaciones
  WHERE creado_en >= DATE_SUB(CURDATE(), INTERVAL p_dias DAY)
  GROUP BY DATE(creado_en)
  ORDER BY fecha ASC;
END$$

-- ─── SP 11: Top obras más vistas ────────────────────────────
CREATE PROCEDURE `sp_top_obras`(IN p_limit INT)
BEGIN
  SELECT o.id, o.titulo, o.visualizaciones, o.valoracion_promedio,
         u.nombre AS artista, c.nombre AS categoria
  FROM obras o
  JOIN usuarios u ON u.id = o.artista_id
  LEFT JOIN categorias c ON c.id = o.categoria_id
  WHERE o.estado = 'aprobada'
  ORDER BY o.visualizaciones DESC
  LIMIT p_limit;
END$$

-- ─── SP 12: Limpiar tokens expirados ────────────────────────
CREATE PROCEDURE `sp_limpiar_tokens`()
BEGIN
  UPDATE usuarios SET token_verificacion = NULL, token_expira = NULL
  WHERE token_expira < NOW() AND verificado = 0;

  UPDATE usuarios SET token_reset = NULL, reset_expira = NULL
  WHERE reset_expira < NOW();

  DELETE FROM cache_estadisticas WHERE expira_en < NOW();

  INSERT INTO bitacora(usuario_id, accion, detalle, ip)
  VALUES(NULL, 'LIMPIEZA_TOKENS', 'Tokens expirados limpiados', 'sistema');
END$$

-- ─── SP 13: Generar estadísticas diarias ────────────────────
CREATE PROCEDURE `sp_generar_estadisticas_diarias`(IN p_fecha DATE)
BEGIN
  INSERT INTO estadisticas_diarias(fecha, nuevos_usuarios, nuevas_obras, total_visitas, ventas_realizadas, ingresos_totales, nuevas_subastas)
  SELECT
    p_fecha,
    (SELECT COUNT(*) FROM usuarios WHERE DATE(creado_en) = p_fecha),
    (SELECT COUNT(*) FROM obras WHERE DATE(creado_en) = p_fecha),
    (SELECT COUNT(*) FROM visualizaciones WHERE DATE(creado_en) = p_fecha),
    (SELECT COUNT(*) FROM ventas WHERE DATE(creado_en) = p_fecha AND estado='completada'),
    (SELECT COALESCE(SUM(monto_total),0) FROM ventas WHERE DATE(creado_en)=p_fecha AND estado='completada'),
    (SELECT COUNT(*) FROM subastas WHERE DATE(creado_en) = p_fecha)
  ON DUPLICATE KEY UPDATE
    nuevos_usuarios = VALUES(nuevos_usuarios),
    nuevas_obras = VALUES(nuevas_obras),
    total_visitas = VALUES(total_visitas),
    ventas_realizadas = VALUES(ventas_realizadas),
    ingresos_totales = VALUES(ingresos_totales),
    nuevas_subastas = VALUES(nuevas_subastas);
END$$

-- ─── SP 14: Bitácora – insertar registro ────────────────────
CREATE PROCEDURE `sp_insertar_bitacora`(
  IN p_usuario_id  INT UNSIGNED,
  IN p_accion      VARCHAR(80),
  IN p_detalle     TEXT,
  IN p_tabla       VARCHAR(60),
  IN p_registro_id INT UNSIGNED,
  IN p_ip          VARCHAR(45),
  IN p_ua          VARCHAR(255)
)
BEGIN
  INSERT INTO bitacora(usuario_id, accion, detalle, tabla_afectada, registro_id, ip, agente_usuario)
  VALUES(p_usuario_id, p_accion, p_detalle, p_tabla, p_registro_id, p_ip, p_ua);
END$$

-- ─── SP 15: Reporte compras para exportar ───────────────────
CREATE PROCEDURE `sp_reporte_compras`(IN p_desde DATE, IN p_hasta DATE)
BEGIN
  SELECT
    v.id, u.nombre AS comprador, u.email,
    o.titulo AS obra, ua.nombre AS artista,
    v.monto_total, v.comision_plataforma, v.monto_artista,
    v.metodo_pago, v.estado, v.tipo, v.creado_en
  FROM ventas v
  JOIN usuarios u  ON u.id  = v.usuario_id
  JOIN obras o     ON o.id  = v.obra_id
  JOIN usuarios ua ON ua.id = v.artista_id
  WHERE DATE(v.creado_en) BETWEEN p_desde AND p_hasta
  ORDER BY v.creado_en DESC;
END$$

DELIMITER ;


-- ============================================================
-- TRIGGERS
-- ============================================================

DELIMITER $$

-- Trigger: actualizar stock edición limitada al vender
CREATE TRIGGER `trg_venta_edicion_stock`
AFTER INSERT ON `ventas`
FOR EACH ROW
BEGIN
  IF NEW.tipo = 'directa' THEN
    UPDATE ediciones_limitadas SET stock_disponible = stock_disponible - 1
    WHERE obra_id = NEW.obra_id AND stock_disponible > 0;
  END IF;
END$$

-- Trigger: auto-actualizar valoracion_promedio en obras
CREATE TRIGGER `trg_valoracion_insert`
AFTER INSERT ON `valoraciones`
FOR EACH ROW
BEGIN
  UPDATE obras
  SET valoracion_promedio = (SELECT AVG(puntuacion) FROM valoraciones WHERE obra_id = NEW.obra_id)
  WHERE id = NEW.obra_id;
END$$

CREATE TRIGGER `trg_valoracion_update`
AFTER UPDATE ON `valoraciones`
FOR EACH ROW
BEGIN
  UPDATE obras
  SET valoracion_promedio = (SELECT AVG(puntuacion) FROM valoraciones WHERE obra_id = NEW.obra_id)
  WHERE id = NEW.obra_id;
END$$

-- Trigger: bitácora automática al eliminar usuario
CREATE TRIGGER `trg_usuario_eliminar_bit`
BEFORE DELETE ON `usuarios`
FOR EACH ROW
BEGIN
  INSERT INTO bitacora(usuario_id, accion, detalle, ip)
  VALUES(OLD.id, 'USUARIO_ELIMINADO', CONCAT('Usuario eliminado: ', OLD.email), 'sistema');
END$$

-- Trigger: bitácora al cambiar rol
CREATE TRIGGER `trg_usuario_rol_cambio`
AFTER UPDATE ON `usuarios`
FOR EACH ROW
BEGIN
  IF OLD.rol != NEW.rol THEN
    INSERT INTO bitacora(usuario_id, accion, detalle, ip)
    VALUES(NEW.id, 'ROL_CAMBIO', CONCAT('Rol cambiado de ', OLD.rol, ' a ', NEW.rol), 'sistema');
  END IF;
END$$

-- Trigger: historial precio al actualizar obra
CREATE TRIGGER `trg_obra_precio_cambio`
AFTER UPDATE ON `obras`
FOR EACH ROW
BEGIN
  IF OLD.precio != NEW.precio OR (OLD.precio IS NULL AND NEW.precio IS NOT NULL) THEN
    INSERT INTO historial_precios(obra_id, precio_anterior, precio_nuevo, cambiado_por)
    VALUES(NEW.id, OLD.precio, NEW.precio, NEW.curador_id);
  END IF;
END$$

-- Trigger: al finalizar subasta → crear venta automática
CREATE TRIGGER `trg_subasta_finalizar`
AFTER UPDATE ON `subastas`
FOR EACH ROW
BEGIN
  IF OLD.estado = 'activa' AND NEW.estado = 'finalizada' AND NEW.ganador_id IS NOT NULL THEN
    CALL sp_registrar_venta(NEW.obra_id, NEW.ganador_id, NEW.precio_actual, 'subasta', 'subasta');
    UPDATE ventas SET subasta_id = NEW.id WHERE obra_id = NEW.obra_id ORDER BY id DESC LIMIT 1;
  END IF;
END$$

-- Trigger: incrementar contador búsqueda
CREATE TRIGGER `trg_busqueda_contador`
AFTER INSERT ON `historial_busquedas`
FOR EACH ROW
BEGIN
  INSERT INTO tags_busqueda(termino, contador)
  VALUES(LOWER(NEW.termino), 1)
  ON DUPLICATE KEY UPDATE contador = contador + 1, actualizado_en = NOW();
END$$

DELIMITER ;


-- ============================================================
-- DATOS SEMILLA (Seeds)
-- ============================================================

-- Categorías
INSERT INTO `categorias` (`nombre`,`descripcion`,`slug`,`activa`,`orden`) VALUES
('Arte Digital',   'Obras creadas digitalmente',         'arte-digital',  1, 1),
('Pintura',        'Obras en lienzo o papel',            'pintura',       1, 2),
('Escultura',      'Obras en tres dimensiones',          'escultura',     1, 3),
('Fotografía',     'Fotografía artística',               'fotografia',    1, 4),
('Ilustración',    'Ilustración y diseño gráfico',       'ilustracion',   1, 5),
('Arte Abstracto', 'Expresión no figurativa',            'abstracto',     1, 6),
('Arte Urbano',    'Arte callejero y grafiti',           'arte-urbano',   1, 7),
('Acuarela',       'Técnica de acuarela',                'acuarela',      1, 8),
('Mixta',          'Técnica mixta',                      'mixta',         1, 9),
('Animación',      'Arte animado y motion graphics',     'animacion',     1,10);

-- Etiquetas
INSERT INTO `etiquetas` (`nombre`,`slug`) VALUES
('naturaleza','naturaleza'),('abstracto','abstracto'),('retrato','retrato'),
('paisaje','paisaje'),('fantasia','fantasia'),('minimalismo','minimalismo'),
('surrealismo','surrealismo'),('pop-art','pop-art'),('ciudad','ciudad'),
('animales','animales'),('colores','colores'),('negro-blanco','negro-blanco');

-- Configuración inicial
INSERT INTO `configuracion` (`clave`,`valor`,`tipo`,`descripcion`) VALUES
('comision_plataforma', '20', 'int',    'Porcentaje de comisión de la plataforma'),
('max_obras_por_artista','50','int',   'Máximo de obras por artista'),
('min_precio_subasta',  '10','int',    'Precio mínimo para subastas'),
('mantenimiento',       '0', 'bool',   'Modo mantenimiento'),
('registro_abierto',    '1', 'bool',   'Registro de nuevos usuarios'),
('smtp_activo',         '1', 'bool',   'Envío de emails activo'),
('items_por_pagina',   '12', 'int',    'Items por página en listados'),
('moneda',           'USD',  'string', 'Moneda principal');

-- Permisos
INSERT INTO `permisos` (`clave`,`descripcion`) VALUES
('ver_galeria',          'Ver galería pública'),
('subir_obra',           'Subir obras'),
('comentar',             'Comentar en obras'),
('pujar',                'Participar en subastas'),
('gestionar_usuarios',   'Gestionar usuarios (admin)'),
('validar_obras',        'Validar obras (curador)'),
('gestionar_exposiciones','Gestionar exposiciones'),
('ver_bitacora',         'Ver bitácora de auditoría'),
('exportar_reportes',    'Exportar PDF/Excel');

-- Usuario admin por defecto (contraseña: Admin123!)
-- El hash corresponde a bcrypt de "Admin123!PEPPER" – reemplaza el hash en producción
INSERT INTO `usuarios` (`nombre`,`email`,`password`,`rol`,`verificado`,`activo`) VALUES
('Administrador','admin@artcania.local',
 '$2y$12$examplehashfortestingonlyXXXXXXXXXXXXXXXXXXXXXXXX',
 'admin',1,1);

-- FAQs básicas
INSERT INTO `faqs`(`pregunta`,`respuesta`,`categoria`,`orden`,`activa`) VALUES
('¿Cómo subo una obra?','Regístrate como artista, ve a tu panel y haz clic en "Subir Obra".','Artistas',1,1),
('¿Cómo compro una obra?','Inicia sesión, encuentra la obra y haz clic en "Comprar".','Coleccionistas',1,1),
('¿Cuánto cobra Artcania?','La plataforma retiene el 20% de cada venta como comisión.','Pagos',1,1),
('¿Puedo retirar mis ganancias?','Sí, desde tu panel de artista puedes solicitar retiros.','Pagos',2,1);

COMMIT;

-- ============================================================
-- VISTAS ÚTILES
-- ============================================================

CREATE OR REPLACE VIEW `v_obras_publicas` AS
SELECT o.*, u.nombre AS artista_nombre, u.avatar AS artista_avatar,
       c.nombre AS categoria_nombre
FROM obras o
JOIN usuarios u ON u.id = o.artista_id
LEFT JOIN categorias c ON c.id = o.categoria_id
WHERE o.estado = 'aprobada';

CREATE OR REPLACE VIEW `v_bitacora_completa` AS
SELECT b.*, u.nombre, u.email, u.rol
FROM bitacora b
LEFT JOIN usuarios u ON u.id = b.usuario_id
ORDER BY b.creado_en DESC;

CREATE OR REPLACE VIEW `v_resumen_artistas` AS
SELECT u.id, u.nombre, u.email, a.especialidad, a.pais,
       COUNT(DISTINCT o.id) AS total_obras,
       SUM(o.visualizaciones) AS total_vistas,
       COUNT(DISTINCT s.id) AS seguidores,
       COALESCE(SUM(v.monto_artista),0) AS ingresos_totales
FROM usuarios u
JOIN artistas a ON a.usuario_id = u.id
LEFT JOIN obras o ON o.artista_id = u.id AND o.estado = 'aprobada'
LEFT JOIN seguimientos s ON s.artista_id = u.id
LEFT JOIN ventas v ON v.artista_id = u.id AND v.estado = 'completada'
WHERE u.rol = 'artista'
GROUP BY u.id;

CREATE OR REPLACE VIEW `v_subastas_activas` AS
SELECT s.*, o.titulo, o.imagen_principal, u.nombre AS artista,
       (SELECT COUNT(*) FROM pujas WHERE subasta_id = s.id) AS total_pujas,
       TIMESTAMPDIFF(HOUR, NOW(), s.fecha_fin) AS horas_restantes
FROM subastas s
JOIN obras o ON o.id = s.obra_id
JOIN usuarios u ON u.id = o.artista_id
WHERE s.estado = 'activa' AND s.fecha_fin > NOW();

-- ============================================================
-- ÍNDICES ADICIONALES PARA PERFORMANCE
-- ============================================================

CREATE INDEX `idx_obras_creado_en` ON `obras`(`creado_en`);
CREATE INDEX `idx_obras_precio` ON `obras`(`precio`);
CREATE INDEX `idx_obras_full` ON `obras`(`estado`,`destacada`,`visualizaciones`);
CREATE INDEX `idx_ventas_fecha` ON `ventas`(`creado_en`);
CREATE INDEX `idx_bit_fecha_acc` ON `bitacora`(`creado_en`,`accion`);
CREATE INDEX `idx_vis_fecha_obra` ON `visualizaciones`(`creado_en`,`obra_id`);