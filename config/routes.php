<?php
/** @var Router $router */

// ── Públicas ─────────────────────────────────────────────────
$router->get('/',                           'PublicController@home');
$router->get('galeria',                     'GalleryController@index');
$router->get('obra/{id}',                   'ArtworkController@show');
$router->get('videos',                      'VideoController@index');
$router->get('fanarts',                     'FanArtController@index');
$router->get('ediciones',                   'EditionController@index');
$router->get('subastas',                    'PublicController@subastas');
$router->get('subasta/{id}',               'PublicController@subastaDetalle');
$router->get('artistas',                    'ArtistController@destacados');
$router->get('artistas/{id}',              'ArtistController@perfilPublicoById');
$router->get('exposiciones',                'ExhibitionController@index');
$router->get('about',                       'PublicController@about');
$router->get('buscar',                      'SearchController@buscar');

// ── Auth ─────────────────────────────────────────────────────
$router->get('login',                       'AuthController@loginForm');
$router->post('login',                      'AuthController@login');
$router->get('registro',                    'AuthController@registerForm');
$router->post('registro',                   'AuthController@register');
$router->get('verificar/{token}',           'AuthController@verify');
$router->get('reenviar-verificacion',        'AuthController@reenviarForm');
$router->post('reenviar-verificacion',       'AuthController@reenviar');
$router->get('recuperar',                   'AuthController@forgotForm');
$router->post('recuperar',                  'AuthController@forgot');
$router->get('reset/{token}',               'AuthController@resetForm');
$router->post('reset',                      'AuthController@reset');

// ── Google Login ──────────────────────────────────────────────
$router->get('auth/google',          'AuthController@googleRedirect');
$router->get('auth/google/callback', 'AuthController@googleCallback');
$router->get('logout',                      'AuthController@logout');

// ── Usuario ───────────────────────────────────────────────────
$router->get('perfil',                      'UserController@perfil');
$router->post('perfil',                     'UserController@actualizar');
$router->get('editar-perfil',               'UserController@editarPerfil');
$router->get('mis-favoritos',               'UserController@misFavoritos');
$router->get('mis-valoraciones',            'UserController@misValoraciones');
$router->get('notificaciones',              'UserController@notificaciones');
$router->get('notificaciones/count',        'UserController@notifCount');
$router->post('notificaciones/leer',        'UserController@marcarLeida');
$router->post('favorito/toggle',            'UserController@toggleFavorito');
$router->post('comentario',                 'ArtworkController@comentar');
$router->post('subasta/pujar',              'ArtworkController@pujar');
$router->get('chat',                        'ChatController@index');
$router->post('chat/enviar',                'ChatController@enviar');
$router->get('chat/mensajes/{id}',          'ChatController@mensajes');
$router->get('mis-conversaciones',          'ChatController@conversaciones');
$router->post('contacto/artista',           'ContactController@enviar');

// ── Artista ───────────────────────────────────────────────────
$router->get('artista/dashboard',           'ArtistController@dashboard');
$router->get('artista/obras',               'ArtworkController@misObras');
$router->post('artista/obras',              'ArtworkController@guardar');
$router->get('artista/obras/{id}/editar',   'ArtworkController@editarForm');
$router->post('artista/obras/{id}',         'ArtworkController@actualizar');
$router->get('artista/metricas',            'ArtistController@metricas');
$router->get('artista/estadisticas',        'ArtistController@estadisticas');
$router->get('artista/perfil-publico',      'ArtistController@perfilPublico');
$router->get('artista/editar-perfil',       'ArtistController@editarPerfil');
$router->post('artista/perfil',             'ArtistController@guardarPerfil');
$router->get('artista/mis-fanarts',         'ArtistController@misFanarts');
$router->get('artista/colaboraciones',      'ArtistController@colaboraciones');
$router->get('artista/premios',             'ArtistController@premios');
$router->get('artista/contactos',           'ArtistController@misContactos');

// ── Curador ───────────────────────────────────────────────────
$router->get('curador/dashboard',           'CuratorController@dashboard');
$router->post('curador/validar',            'CuratorController@validar');
$router->get('curador/obras-pendientes',    'CuratorController@obrasPendientes');
$router->get('curador/historial',           'CuratorController@historial');
$router->get('curador/exposiciones',        'CuratorController@gestionExposiciones');
$router->post('curador/exposicion',         'CuratorController@crearExposicion');
$router->get('curador/destacados',          'CuratorController@destacados');
$router->post('curador/destacar',           'CuratorController@destacar');
$router->get('curador/comentarios',         'CuratorController@moderarComentarios');
$router->post('curador/comentario/aprobar', 'CuratorController@aprobarComentario');
$router->post('curador/comentario/rechazar','CuratorController@rechazarComentario');
$router->get('curador/reportes',            'CuratorController@reportes');
$router->get('curador/metricas',            'CuratorController@metricas');

// ── Admin ─────────────────────────────────────────────────────
$router->get('admin/dashboard',             'AdminController@dashboard');
$router->get('admin/usuarios',              'AdminController@usuarios');
$router->get('admin/roles',                 'AdminController@gestionRoles');
$router->get('admin/artistas',              'AdminController@gestionArtistas');
$router->get('admin/curadores',             'AdminController@gestionCuradores');
$router->get('admin/obras',                 'AdminController@gestionObras');
$router->get('admin/fanarts',               'AdminController@gestionFanarts');
$router->get('admin/exposiciones',          'AdminController@gestionExposiciones');
$router->get('admin/subastas',              'AdminController@gestionSubastas');
$router->get('admin/chat',                  'AdminController@gestionChat');
$router->get('admin/contactos',             'AdminController@gestionContactos');
$router->get('admin/bitacora',              'AdminController@bitacora');
$router->get('admin/respaldos',             'AdminController@respaldos');
$router->get('admin/configuracion',         'AdminController@configuracion');
$router->post('admin/toggle-usuario',       'AdminController@toggleUsuario');
$router->post('admin/cambiar-rol',          'AdminController@cambiarRol');
$router->post('admin/verificar-usuario',    'AdminController@verificarUsuario');
$router->post('admin/respaldo/crear',       'AdminController@crearRespaldo');
$router->post('admin/configuracion',        'AdminController@guardarConfiguracion');

// ── Reportes PDF / Excel ──────────────────────────────────────
$router->get('reporte/bitacora/pdf',        'ReportController@bitacoraPDF');
$router->get('reporte/bitacora/excel',      'ReportController@bitacoraExcel');
$router->get('reporte/compras/pdf',         'ReportController@comprasPDF');
$router->get('reporte/compras/excel',       'ReportController@comprasExcel');
$router->get('reporte/estadisticas/pdf',    'ReportController@statsPDF');
$router->get('reporte/estadisticas/excel',  'ReportController@statsExcel');
