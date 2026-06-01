<?php 
$pageTitle = 'Publicar nueva obra'; 

// Evita errores si las variables no fueron enviadas desde el controlador
$obra = $obra ?? [];
$categorias = $categorias ?? [];
?>

<div class="container py-4">

  <!-- Encabezado de la vista -->
  <div class="d-flex align-items-center gap-3 mb-3">

    <!-- Botón de regreso -->
    <a href="<?= url('artista/mis-obras') ?>" class="btn-magic" style="padding:.45rem .8rem">
      <i class="fa fa-arrow-left"></i>
    </a>

    <div>
      <h2 style="font-family:'Cinzel',serif;color:var(--moon);margin:0;letter-spacing:.05em">
        Publicar nueva obra
      </h2>

      <small style="color:rgba(212,184,255,.65);font-style:italic">
        Comparte tu arte con el universo
      </small>
    </div>
  </div>

  <!-- Formulario principal -->
  <form method="POST"
        action="<?= url('artista/obra/guardar') ?>"
        enctype="multipart/form-data">

    <!-- Protección CSRF -->
    <?= csrf_field() ?>

    <div class="row g-4">

      <!-- Columna principal -->
      <div class="col-lg-8">

        <div class="glass-panel">

          <!-- Zona de carga de archivos -->
          <label class="dropzone-magic d-block">

            <input type="file"
                   name="imagen"
                   accept="image/*,video/*"
                   hidden>

            <div class="cloud">
              <i class="fa fa-cloud-arrow-up"></i>
            </div>

            <h4 style="font-family:'Cinzel',serif;color:var(--moon)">
              Arrastra tu obra aquí
            </h4>

            <small style="color:var(--lavender)">
              o haz clic para seleccionar un archivo
            </small>

            <div class="mt-2"
                 style="color:rgba(212,184,255,.55);font-size:.85rem">
              Formatos aceptados: JPG, PNG, WEBP, MP4, GIF, GLB
              <br>
              Tamaño máximo: 100MB
            </div>

          </label>

          <div class="row g-3 mt-3">

            <!-- Título -->
            <div class="col-md-6">
              <label style="color:var(--lavender)">
                Título de la obra *
              </label>

              <div class="position-relative">
                <i class="fa fa-pen position-absolute"
                   style="top:14px;left:14px;color:var(--magenta)">
                </i>

                <input
                  name="titulo"
                  maxlength="100"
                  required
                  class="input-magic"
                  placeholder="Escribe el título de tu obra"
                  style="padding-left:40px"
                  value="<?= e($obra['titulo'] ?? '') ?>"
                >
              </div>
            </div>

            <!-- Etiquetas -->
            <div class="col-md-6">
              <label style="color:var(--lavender)">
                Etiquetas
              </label>

              <div class="position-relative">
                <i class="fa fa-tag position-absolute"
                   style="top:14px;left:14px;color:var(--magenta)">
                </i>

                <input
                  name="etiquetas"
                  class="input-magic"
                  placeholder="Añade etiquetas (ej: fantasía, retrato, surrealismo...)"
                  style="padding-left:40px"
                >
              </div>

              <small style="color:rgba(212,184,255,.5)">
                Presiona Enter para agregar cada etiqueta
              </small>
            </div>

            <!-- Descripción -->
            <div class="col-md-6">
              <label style="color:var(--lavender)">
                Descripción *
              </label>

              <div class="position-relative">
                <i class="fa fa-book position-absolute"
                   style="top:14px;left:14px;color:var(--magenta)">
                </i>

                <textarea
                  name="descripcion"
                  rows="5"
                  maxlength="2000"
                  required
                  class="input-magic"
                  placeholder="Cuéntanos sobre tu obra, inspiración, técnica, concepto..."
                  style="padding-left:40px"><?= e($obra['descripcion'] ?? '') ?></textarea>
              </div>
            </div>

            <!-- Precio -->
            <div class="col-md-6">
              <label style="color:var(--lavender)">
                Precio *
              </label>

              <div class="d-flex gap-2">
                <select class="input-magic" style="max-width:90px">
                  <option>USD</option>
                  <option>EUR</option>
                </select>

                <input
                  name="precio"
                  type="number"
                  step="0.01"
                  class="input-magic"
                  placeholder="0.00"
                  value="<?= e($obra['precio'] ?? '') ?>"
                >
              </div>

              <small style="color:rgba(212,184,255,.5)">
                Establece el precio de tu obra en USD
              </small>

              <!-- Categorías -->
              <div class="mt-3">
                <label style="color:var(--lavender)">
                  Categoría *
                </label>

                <div class="position-relative">
                  <i class="fa fa-gem position-absolute"
                     style="top:14px;left:14px;color:var(--magenta)">
                  </i>

                  <select name="categoria_id"
                          required
                          class="input-magic"
                          style="padding-left:40px">

                    <option value="">
                      Selecciona una categoría
                    </option>

                    <?php foreach($categorias as $c): ?>
                      <option value="<?= e($c['id']) ?>">
                        <?= e($c['nombre']) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <!-- Edición limitada -->
              <div class="mt-3">
                <label style="color:var(--lavender)">
                  Edición limitada
                </label>

                <div class="form-check"
                     style="background:rgba(12,6,30,.5);
                            padding:.7rem 1rem 0 2.5rem;
                            border-radius:12px;
                            border:1px solid rgba(212,184,255,.18)">

                  <input class="form-check-input"
                         type="checkbox"
                         name="edicion_limitada"
                         id="ed">

                  <label class="form-check-label"
                         for="ed"
                         style="color:var(--moon)">
                    ✦ Esta obra es parte de una edición limitada
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Botón publicar -->
        <div class="text-center mt-4">

          <button class="btn-magic btn-magic-solid"
                  style="padding:1rem 3rem;font-size:1.1rem">
            ✦ Publicar obra ✦
          </button>
        </div>
      </div>

      <!-- Vista previa lateral -->
      <div class="col-lg-4">
        <div class="glass-panel"
             style="border:1px solid rgba(255,213,138,.3)">
          <div class="text-center mb-2"
               style="color:var(--gold);
                      font-family:'Cinzel',serif;
                      letter-spacing:.1em">
            ✦ Vista previa ✦
          </div>

          <div style="aspect-ratio:3/4;
                      border-radius:14px;
                      background:radial-gradient(circle at center,#7a3df0,#1a0a40 70%,#06030f);
                      display:flex;
                      align-items:center;
                      justify-content:center">
            <div style="color:var(--magenta);
                        font-size:3rem">
              ✦
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
