-- ═══════════════════════════════════════════════════════════════════════
-- MIGRATION 002: Seguridad + Google Login
-- Ejecutar en MySQL Workbench sobre la base artcania
-- ═══════════════════════════════════════════════════════════════════════

USE artcania;
-- 1) Columnas para Google Login
ALTER TABLE `usuarios`
  ADD COLUMN IF NOT EXISTS `google_id` VARCHAR(100)  NULL DEFAULT NULL AFTER `password`,
  ADD COLUMN IF NOT EXISTS `avatar`    VARCHAR(500)  NULL DEFAULT NULL AFTER `google_id`;

-- 2) Permitir password NULL (usuarios que solo usan Google)
ALTER TABLE `usuarios`
  MODIFY COLUMN `password` VARCHAR(255) NULL DEFAULT NULL;

-- 3) Índice para google_id
ALTER TABLE `usuarios`
  ADD INDEX IF NOT EXISTS `idx_google_id` (`google_id`);

-- 4) Actualizar sp_login_fallido: 3 intentos → 1 hora de bloqueo
DROP PROCEDURE IF EXISTS `sp_login_fallido`;

DELIMITER $$
CREATE PROCEDURE `sp_login_fallido`(IN p_id INT UNSIGNED)
BEGIN
  DECLARE v_intentos TINYINT UNSIGNED;
  UPDATE usuarios SET intentos_fallidos = intentos_fallidos + 1 WHERE id = p_id;
  SELECT intentos_fallidos INTO v_intentos FROM usuarios WHERE id = p_id;
  IF v_intentos >= 3 THEN
    UPDATE usuarios SET bloqueado_hasta = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE id = p_id;
    INSERT INTO bitacora(usuario_id, accion, detalle, ip)
    VALUES(p_id, 'CUENTA_BLOQUEADA', CONCAT('Bloqueada 1h por ', v_intentos, ' intentos'), '');
  ELSE
    INSERT INTO bitacora(usuario_id, accion, detalle, ip)
    VALUES(p_id, 'LOGIN_FALLIDO', CONCAT('Intento #', v_intentos, ' de 3'), '');
  END IF;
END$$
DELIMITER ;

-- 5) Actualizar sp_verificar_email para retornar rowCount > 0
DROP PROCEDURE IF EXISTS `sp_verificar_email`;
DELIMITER $$
CREATE PROCEDURE `sp_verificar_email`(IN p_token VARCHAR(128))
BEGIN
  UPDATE usuarios
    SET verificado = 1, token_verificacion = NULL, token_expira = NULL
    WHERE token_verificacion = p_token AND token_expira > NOW() AND verificado = 0;
END$$
DELIMITER ;
