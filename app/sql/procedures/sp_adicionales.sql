-- ============================================================
-- Artcania – Stored Procedures Adicionales
-- Complementa los SPs del script principal
-- ============================================================
USE artcania;

DELIMITER $$

-- SP: Obtener perfil completo de artista
CREATE PROCEDURE IF NOT EXISTS `sp_perfil_artista`(IN p_usuario_id INT UNSIGNED)
BEGIN
    SELECT
        u.id, u.nombre, u.email, u.avatar, u.bio, u.creado_en,
        a.especialidad, a.pais, a.ciudad, a.sitio_web, a.instagram,
        a.destacado, a.verificado, a.puntuacion,
        COUNT(DISTINCT o.id)              AS total_obras,
        SUM(o.visualizaciones)            AS total_vistas,
        COUNT(DISTINCT s.id)              AS total_seguidores,
        COALESCE(AVG(o.valoracion_promedio),0) AS valoracion_media,
        COALESCE(SUM(v.monto_artista),0)  AS ingresos_totales
    FROM usuarios u
    JOIN artistas a ON a.usuario_id = u.id
    LEFT JOIN obras o ON o.artista_id = u.id AND o.estado = 'aprobada'
    LEFT JOIN seguimientos s ON s.artista_id = u.id
    LEFT JOIN ventas v ON v.artista_id = u.id AND v.estado = 'completada'
    WHERE u.id = p_usuario_id
    GROUP BY u.id;
END$$

-- SP: Buscar obras con filtros avanzados
CREATE PROCEDURE IF NOT EXISTS `sp_buscar_obras`(
    IN p_q          VARCHAR(200),
    IN p_cat        INT UNSIGNED,
    IN p_precio_min DECIMAL(12,2),
    IN p_precio_max DECIMAL(12,2),
    IN p_tipo       VARCHAR(20),
    IN p_limit      INT,
    IN p_offset     INT
)
BEGIN
    SET p_q = CONCAT('%', COALESCE(p_q,''), '%');
    SELECT o.*, u.nombre AS artista, c.nombre AS categoria
    FROM obras o
    JOIN usuarios u ON u.id = o.artista_id
    LEFT JOIN categorias c ON c.id = o.categoria_id
    WHERE o.estado = 'aprobada'
      AND (p_q = '%%' OR o.titulo LIKE p_q OR o.descripcion LIKE p_q OR o.tecnica LIKE p_q)
      AND (p_cat = 0 OR o.categoria_id = p_cat)
      AND (p_precio_min IS NULL OR o.precio >= p_precio_min)
      AND (p_precio_max IS NULL OR o.precio <= p_precio_max)
      AND (p_tipo = '' OR o.tipo = p_tipo)
    ORDER BY o.visualizaciones DESC, o.creado_en DESC
    LIMIT p_limit OFFSET p_offset;
END$$

-- SP: Dashboard artista
CREATE PROCEDURE IF NOT EXISTS `sp_dashboard_artista`(IN p_artista_id INT UNSIGNED)
BEGIN
    SELECT
        (SELECT COUNT(*) FROM obras WHERE artista_id=p_artista_id) AS total_obras,
        (SELECT COUNT(*) FROM obras WHERE artista_id=p_artista_id AND estado='aprobada') AS obras_aprobadas,
        (SELECT COUNT(*) FROM obras WHERE artista_id=p_artista_id AND estado='pendiente') AS obras_pendientes,
        (SELECT COALESCE(SUM(visualizaciones),0) FROM obras WHERE artista_id=p_artista_id) AS total_vistas,
        (SELECT COUNT(*) FROM seguimientos WHERE artista_id=p_artista_id) AS seguidores,
        (SELECT COALESCE(SUM(monto_artista),0) FROM ventas WHERE artista_id=p_artista_id AND estado='completada') AS ingresos,
        (SELECT COUNT(*) FROM contactos_artista WHERE artista_id=p_artista_id AND leido=0) AS mensajes_no_leidos;
END$$

-- SP: Verificar y consumir cupón
CREATE PROCEDURE IF NOT EXISTS `sp_usar_cupon`(
    IN p_codigo     VARCHAR(30),
    IN p_usuario_id INT UNSIGNED,
    IN p_monto      DECIMAL(12,2),
    OUT p_descuento DECIMAL(12,2),
    OUT p_cupon_id  INT UNSIGNED
)
BEGIN
    DECLARE v_id INT UNSIGNED;
    DECLARE v_desc_pct DECIMAL(5,2);
    DECLARE v_desc_fijo DECIMAL(10,2);
    DECLARE v_uso_max SMALLINT;
    DECLARE v_usado SMALLINT;

    SET p_descuento = 0; SET p_cupon_id = 0;

    SELECT id, descuento_pct, descuento_fijo, uso_maximo, veces_usado
    INTO v_id, v_desc_pct, v_desc_fijo, v_uso_max, v_usado
    FROM cupones
    WHERE codigo = p_codigo AND activo = 1
      AND (valido_desde IS NULL OR valido_desde <= NOW())
      AND (valido_hasta IS NULL OR valido_hasta >= NOW())
    LIMIT 1;

    IF v_id IS NOT NULL AND v_usado < v_uso_max THEN
        SET p_cupon_id = v_id;
        IF v_desc_pct IS NOT NULL THEN
            SET p_descuento = ROUND(p_monto * v_desc_pct / 100, 2);
        ELSEIF v_desc_fijo IS NOT NULL THEN
            SET p_descuento = LEAST(v_desc_fijo, p_monto);
        END IF;
        UPDATE cupones SET veces_usado = veces_usado + 1 WHERE id = v_id;
    END IF;
END$$

-- SP: Metricas mensuales artista
CREATE PROCEDURE IF NOT EXISTS `sp_metricas_mes_artista`(IN p_artista_id INT UNSIGNED, IN p_mes CHAR(7))
BEGIN
    SELECT
        COUNT(DISTINCT v.id)                        AS ventas,
        COALESCE(SUM(v.monto_artista),0)            AS ingresos,
        (SELECT COUNT(*) FROM seguimientos WHERE artista_id=p_artista_id AND DATE_FORMAT(creado_en,'%Y-%m')=p_mes) AS nuevos_seguidores,
        (SELECT COALESCE(SUM(vis.total),0) FROM (SELECT COUNT(*) as total FROM visualizaciones vi JOIN obras o ON o.id=vi.obra_id WHERE o.artista_id=p_artista_id AND DATE_FORMAT(vi.creado_en,'%Y-%m')=p_mes) vis) AS total_vistas,
        COUNT(DISTINCT ow.id)                       AS obras_nuevas
    FROM ventas v
    RIGHT JOIN obras ow ON ow.artista_id = p_artista_id AND DATE_FORMAT(ow.creado_en,'%Y-%m') = p_mes
    WHERE (v.artista_id = p_artista_id AND DATE_FORMAT(v.creado_en,'%Y-%m') = p_mes AND v.estado='completada') OR v.id IS NULL;
END$$

DELIMITER ;

-- Event scheduler: limpiar tokens cada noche
CREATE EVENT IF NOT EXISTS `evt_limpiar_tokens`
ON SCHEDULE EVERY 1 DAY STARTS CURRENT_TIMESTAMP
DO CALL sp_limpiar_tokens();

-- Event scheduler: generar estadísticas diarias
CREATE EVENT IF NOT EXISTS `evt_estadisticas_diarias`
ON SCHEDULE EVERY 1 DAY STARTS (CURDATE() + INTERVAL 1 DAY + INTERVAL 1 HOUR)
DO CALL sp_generar_estadisticas_diarias(DATE_SUB(CURDATE(), INTERVAL 1 DAY));

-- Activar event scheduler (ejecutar si no está activo)
-- SET GLOBAL event_scheduler = ON;
