-- ============================================================
-- ARTCANIA – Datos de prueba (Seeds)
-- Ejecutar DESPUÉS de artcania_database.sql
-- ============================================================
USE artcania;

-- Contraseñas: todas son "Test123!" + pepper "CHANGE_ME_PEPPER_SECRET"
-- Para usar en pruebas, genera el hash real con Auth::hashPassword()
-- Aquí usamos un placeholder; actualizar con:
-- UPDATE usuarios SET password = '$2y$12$...' WHERE email = '...';

-- Usuarios de prueba
INSERT IGNORE INTO `usuarios` (`nombre`,`email`,`password`,`rol`,`verificado`,`activo`) VALUES
('Ana Martínez',    'artista@artcania.local',  '$2y$12$placeholder_hash_artista',  'artista',  1, 1),
('Carlos Mendoza',  'curador@artcania.local',  '$2y$12$placeholder_hash_curador',  'curador',  1, 1),
('Laura Sánchez',   'usuario@artcania.local',  '$2y$12$placeholder_hash_usuario',  'usuario',  1, 1),
('Diego Ramírez',   'artista2@artcania.local', '$2y$12$placeholder_hash_artista2', 'artista',  1, 1),
('Sofía Torres',    'artista3@artcania.local', '$2y$12$placeholder_hash_artista3', 'artista',  1, 1);

-- Perfiles de artistas
INSERT IGNORE INTO `artistas` (`usuario_id`,`especialidad`,`pais`,`ciudad`,`descripcion`,`destacado`,`verificado`) VALUES
(2, 'Arte Digital y Pintura', 'México',    'Ciudad de México', 'Artista digital con 10 años de experiencia en ilustración fantástica.',     1, 1),
(5, 'Escultura y Arte Mixto', 'Argentina', 'Buenos Aires',     'Escultor contemporáneo especializado en técnicas mixtas y arte urbano.',    0, 1),
(6, 'Acuarela y Fotografía',  'Colombia',  'Bogotá',           'Artista multimedia que fusiona fotografía análoga con ilustración digital.', 1, 1);

-- Curador
INSERT IGNORE INTO `curadores` (`usuario_id`,`especialidad`) VALUES
(3, 'Arte Contemporáneo y Digital');

-- Obras de muestra
INSERT IGNORE INTO `obras` (`artista_id`,`categoria_id`,`titulo`,`descripcion`,`tecnica`,`dimensiones`,`precio`,`estado`,`visualizaciones`,`valoracion_promedio`,`destacada`,`tipo`) VALUES
(2, 1, 'Sueños de Neón',        'Una exploración del mundo urbano nocturno visto desde la perspectiva digital.', 'Arte Digital',     '4K 3840x2160px', 1200.00, 'aprobada', 342,  4.5, 1, 'digital'),
(2, 1, 'Galaxia Interior',      'Viaje introspectivo a través del cosmos de la mente humana.',                   'Arte Digital',     '4K 3840x2160px',  850.00, 'aprobada', 189,  4.2, 0, 'digital'),
(2, 5, 'Retrato en Azul',       'Estudio del rostro humano en tonalidades frías.',                               'Ilustración',      'A3 300dpi',       650.00, 'aprobada', 421,  4.8, 1, 'digital'),
(5, 2, 'La Ciudad Duerme',      'Obra en lienzo que captura la quietud urbana al amanecer.',                     'Óleo sobre lienzo','80x60 cm',       2500.00, 'aprobada', 156,  4.1, 0, 'lienzo'),
(5, 3, 'Forma y Vacío',         'Escultura que dialoga con el espacio negativo.',                                'Mármol sintético', '40x20x20 cm',    4800.00, 'aprobada',  78,  4.7, 1, 'escultura'),
(6, 4, 'Instante Perpetuo',     'Fotografía artística en blanco y negro de alto contraste.',                     'Fotografía análoga','50x70 cm',       1100.00, 'aprobada', 267,  4.4, 0, 'digital'),
(6, 8, 'Jardín de Lluvia',      'Serie de acuarelas inspiradas en jardines japoneses.',                          'Acuarela',         '30x40 cm',        450.00, 'aprobada', 312,  4.6, 0, 'lienzo'),
(2, 1, 'Noche Cuántica',        'Abstracción digital inspirada en física cuántica.',                             'Arte Digital',     '4K',              980.00, 'pendiente',   0,  0.0, 0, 'digital'),
(5, 6, 'Disonancia Cromática',  'Estudio de colores que se repelen y atraen.',                                   'Pintura acrílica', '100x80 cm',      1800.00, 'aprobada', 203,  4.3, 0, 'lienzo'),
(6, 1, 'Pixel Dreams',          'Arte generativo inspirado en los sueños lúcidos.',                              'Arte Generativo',  'Variable',         750.00, 'aprobada', 445,  4.9, 1, 'digital');

-- Etiquetas a obras
INSERT IGNORE INTO `obra_etiquetas` (`obra_id`,`etiqueta_id`) VALUES
(1,1),(1,9),(1,11),(2,1),(2,6),(3,3),(4,9),(5,2),(6,12),(7,1),(9,2),(9,11),(10,6);

-- Categorías a obras ya establecidas via INSERT obras (categoria_id)

-- Comentarios aprobados
INSERT IGNORE INTO `comentarios` (`obra_id`,`usuario_id`,`contenido`,`estado`) VALUES
(1, 4, '¡Una obra magistral! Los colores neón crean una atmósfera única y envolvente.', 'aprobado'),
(1, 3, 'Me parece fascinante cómo logras transmitir la energía de la ciudad nocturna.', 'aprobado'),
(3, 4, 'El retrato tiene una profundidad emocional impresionante. Los azules son perfectos.', 'aprobado'),
(6, 3, 'La fotografía captura un instante con una honestidad que pocas obras logran.', 'aprobado'),
(7, 4, 'El jardín de lluvia me transportó completamente a Kioto. ¡Extraordinario!', 'aprobado');

-- Valoraciones
INSERT IGNORE INTO `valoraciones` (`obra_id`,`usuario_id`,`puntuacion`) VALUES
(1, 4, 5),(2, 4, 4),(3, 3, 5),(4, 4, 4),(5, 3, 5),(6, 4, 4),(7, 3, 5),(9, 4, 4),(10, 3, 5);

-- Favoritos
INSERT IGNORE INTO `favoritos` (`usuario_id`,`obra_id`) VALUES
(4, 1),(4, 3),(4, 5),(4, 10),(3, 2),(3, 7);

-- Seguimientos
INSERT IGNORE INTO `seguimientos` (`seguidor_id`,`artista_id`) VALUES
(4, 2),(4, 6),(3, 2),(3, 5);

-- Notificaciones de prueba
INSERT IGNORE INTO `notificaciones` (`usuario_id`,`tipo`,`mensaje`,`referencia_id`,`leida`) VALUES
(2, 'obra_aprobada',  '¡Tu obra "Sueños de Neón" fue aprobada!',              1, 1),
(2, 'obra_aprobada',  '¡Tu obra "Galaxia Interior" fue aprobada!',            2, 0),
(4, 'comentario',     'Alguien comentó en una obra que sigues.',               1, 0),
(2, 'venta',          '¡Felicidades! Se vendió "Retrato en Azul" por $650.',   3, 0);

-- Subasta activa de ejemplo
INSERT IGNORE INTO `subastas` (`obra_id`,`precio_inicial`,`precio_actual`,`fecha_inicio`,`fecha_fin`,`estado`) VALUES
(2, 500.00, 720.00, DATE_SUB(NOW(),INTERVAL 2 DAY), DATE_ADD(NOW(),INTERVAL 5 DAY), 'activa'),
(9, 800.00, 800.00, DATE_ADD(NOW(),INTERVAL 1 DAY), DATE_ADD(NOW(),INTERVAL 8 DAY), 'programada');

-- Pujas en subasta activa
INSERT IGNORE INTO `pujas` (`subasta_id`,`usuario_id`,`monto`) VALUES
(1, 4, 600.00),
(1, 3, 650.00),
(1, 4, 720.00);

-- Actualizar ganador subasta
UPDATE `subastas` SET `ganador_id`=4, `precio_actual`=720.00 WHERE `id`=1;

-- Edición limitada
INSERT IGNORE INTO `ediciones_limitadas` (`obra_id`,`titulo`,`descripcion`,`precio`,`stock_total`,`stock_disponible`,`activa`) VALUES
(1, 'Print Premium – Sueños de Neón',    'Impresión fine art 50x70 cm en papel muséo. Numerada y firmada.',  350.00, 20, 20, 1),
(3, 'Print – Retrato en Azul',           'Giclée 30x40 cm. Edición limitada de 15 copias.',                 180.00, 15, 14, 1),
(7, 'Original – Jardín de Lluvia #3',    'Obra original en acuarela, única pieza.',                          450.00,  1,  1, 1);

-- Evento próximo
INSERT IGNORE INTO `eventos` (`titulo`,`descripcion`,`organizador_id`,`fecha_inicio`,`fecha_fin`,`virtual`,`url_acceso`,`activo`) VALUES
('Noche de Arte Digital 2025', 'Exhibición virtual de los mejores artistas digitales de la plataforma.', 3, DATE_ADD(NOW(),INTERVAL 15 DAY), DATE_ADD(NOW(),INTERVAL 16 DAY), 1, 'https://artcania.local/eventos/noche-digital', 1);

-- Taller disponible
INSERT IGNORE INTO `talleres` (`titulo`,`descripcion`,`instructor_id`,`precio`,`capacidad`,`fecha_inicio`,`virtual`) VALUES
('Arte Digital desde Cero', 'Aprende los fundamentos del arte digital usando herramientas gratuitas.', 2, 150.00, 25, DATE_ADD(NOW(),INTERVAL 20 DAY), 1);

-- Galería curada
INSERT IGNORE INTO `galerias` (`nombre`,`descripcion`,`curador_id`,`publica`) VALUES
('Colección Primavera 2025', 'Las mejores obras de arte digital de la temporada.', 3, 1);

INSERT IGNORE INTO `galeria_obras` (`galeria_id`,`obra_id`,`orden`) VALUES
(1,1,1),(1,3,2),(1,6,3),(1,10,4);

-- Exposición virtual
INSERT IGNORE INTO `exposiciones` (`titulo`,`descripcion`,`curador_id`,`fecha_inicio`,`fecha_fin`,`publica`,`tipo`) VALUES
('Lo Eterno en lo Digital', 'Una reflexión sobre la permanencia del arte en la era digital.', 3, CURDATE(), DATE_ADD(CURDATE(),INTERVAL 30 DAY), 1, 'virtual');

INSERT IGNORE INTO `exposicion_obras` (`exposicion_id`,`obra_id`,`orden`) VALUES
(1,1,1),(1,2,2),(1,10,3);

-- Estadísticas diarias de los últimos 7 días
INSERT IGNORE INTO `estadisticas_diarias` (`fecha`,`nuevos_usuarios`,`nuevas_obras`,`total_visitas`,`ventas_realizadas`,`ingresos_totales`) VALUES
(DATE_SUB(CURDATE(),INTERVAL 6 DAY), 3,  2, 145, 1,  850.00),
(DATE_SUB(CURDATE(),INTERVAL 5 DAY), 5,  3, 198, 2, 1450.00),
(DATE_SUB(CURDATE(),INTERVAL 4 DAY), 2,  1,  87, 0,     0.00),
(DATE_SUB(CURDATE(),INTERVAL 3 DAY), 8,  4, 312, 3, 2200.00),
(DATE_SUB(CURDATE(),INTERVAL 2 DAY), 4,  2, 256, 1,  650.00),
(DATE_SUB(CURDATE(),INTERVAL 1 DAY), 6,  3, 423, 2, 1100.00),
(CURDATE(),                          2,  1, 134, 0,     0.00);

-- Configurar comisión personalizada para artista destacada
INSERT IGNORE INTO `comisiones_artista` (`artista_id`,`porcentaje`) VALUES (2, 85.00);

-- Premio para artista
INSERT IGNORE INTO `premios_artista` (`artista_id`,`titulo`,`descripcion`,`anio`,`otorgado_por`) VALUES
(2, 'Artista Destacada del Mes', 'Reconocimiento por excelencia artística y engagement en la plataforma.', YEAR(NOW()), 'Artcania'),
(6, 'Premio Innovación Visual', 'Por la fusión única de técnicas fotográficas y digitales.', YEAR(NOW()), 'Artcania');

-- Banner principal
INSERT IGNORE INTO `banners` (`titulo`,`imagen`,`url_destino`,`posicion`,`activo`,`orden`) VALUES
('Descubre Arte Único', 'banner_hero.jpg', '/galeria', 1, 1, 1),
('Subastas en Vivo',    'banner_sub.jpg',  '/subastas', 2, 1, 2);

-- FAQs adicionales
INSERT IGNORE INTO `faqs`(`pregunta`,`respuesta`,`categoria`,`orden`,`activa`) VALUES
('¿Cómo verifico mi correo?', 'Recibirás un enlace al registrarte. Haz clic en él para activar tu cuenta.','Cuenta',1,1),
('¿Puedo cancelar una puja?', 'Las pujas son vinculantes. Contacta soporte si tienes un problema grave.','Subastas',2,1),
('¿Qué es una edición limitada?','Son copias numeradas y certificadas de una obra original, con stock reducido.','Colecciones',1,1);

-- Mensaje de conversación de prueba
INSERT IGNORE INTO `conversaciones_chat` (`usuario1_id`,`usuario2_id`) VALUES (4, 2);
INSERT IGNORE INTO `mensajes_chat` (`conversacion_id`,`remitente_id`,`mensaje`,`leido`) VALUES
(1, 4, 'Hola Ana, me encantó tu obra "Sueños de Neón". ¿Haces comisiones?', 1),
(1, 2, '¡Gracias! Sí, acepto comisiones. Cuéntame qué tienes en mente.', 1),
(1, 4, 'Quisiera algo similar pero con temática del océano profundo.', 0);

-- Contacto a artista de prueba
INSERT IGNORE INTO `contactos_artista` (`artista_id`,`usuario_id`,`asunto`,`mensaje`) VALUES
(2, 4, 'Consulta sobre comisión', 'Hola, me interesa encargar una obra personalizada. ¿Cuáles son tus tarifas y plazos de entrega?');