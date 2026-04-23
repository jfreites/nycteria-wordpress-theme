# AGENTS.md — Nycteria Store Theme

Guía de referencia para agentes de IA y colaboradores. Usarla antes de generar cualquier feature para asegurar consistencia con el desarrollo actual.

---

## Identidad del tema

| Campo | Valor |
|---|---|
| Theme Name | Nycteria Store |
| Text Domain | `nycteria-store` |
| PHP Function prefix | `nycteria_store_` |
| CSS class prefix | Ver §CSS Naming más abajo |
| Base | Underscores (`_s`) |
| Autor | Ungravity Dev |
| Versión | 1.0.0 |
| PHP mínimo | 5.6 |

**Concepto de marca:** Moda oscura / boutique gótica. Estética oscura, elegante y editorial. Toda copia, textos por defecto y decisiones de diseño deben reflejar este tono.

---

## Stack técnico

- **PHP** — WordPress template hierarchy estándar + WooCommerce
- **CSS** — Una hoja de estilos principal (`style.css`) + `woocommerce.css`. Compilado desde `sass/` con `node-sass`
- **JS** — Vanilla JS (`js/navigation.js`, `js/customizer.js`). Sin jQuery en el front-end excepto donde WordPress lo inyecta
- **Fuentes** — Google Fonts (cargadas en `nycteria_store_scripts()`)
- **Plugins soportados** — WooCommerce (requerido para la tienda), Jetpack (opcional), Yoast SEO / Rank Math (opcional, breadcrumbs)

### Comandos de desarrollo

```bash
# Compilar SCSS a CSS (watch)
npm run watch

# Compilar una vez
npm run compile:css

# Lint SCSS
npm run lint:scss

# Lint JS
npm run lint:js
```

---

## Paleta de colores (CSS Custom Properties)

Todas las propiedades están en `:root` dentro de `style.css`. **No usar valores hexadecimales directos** — usar siempre las variables.

```css
/* Fondos */
--bg-base: #0A0A0A          /* fondo principal de página */
--bg-surface: #121212       /* superficies elevadas (cards, etc.) */
--bg-elevated: #1E1E1E      /* elementos aún más elevados */

/* Marca — Rojo sangre */
--brand-primary: #c52020
--brand-light: #e04a4a
--brand-dark: #8f1515

/* Acento — Púrpura */
--accent: #6A2C70
--accent-light: #A58AAE
--purple-dark: #4B1E4D

/* Texto */
--text-main: #FFFFFF
--text-muted: #CCCCCC
--text-dim: #888888

/* Bordes */
--border-subtle: rgba(255, 255, 255, 0.1)
--border-dim: rgba(255, 255, 255, 0.2)

/* Status */
--status-success: #0f834d
--status-error: #e2401c
--status-info: #3d9cd2
```

### Escala tipográfica fluida

```css
--step--2  /* ~0.64–0.72rem */
--step--1  /* ~0.8–0.9rem */
--step-0   /* ~1–1.13rem  (base) */
--step-1   /* ~1.25–1.41rem */
--step-2   /* ~1.56–1.76rem */
--step-3   /* ~1.95–2.2rem */
--step-4   /* ~2.44–2.75rem */
--step-5   /* ~3.05–3.44rem */
```

---

## Tipografía

```css
--font-heading: "Playfair Display", serif   /* encabezados editoriales */
--font-body: "Montserrat", sans-serif       /* cuerpo de texto */
--font-gothic-title: "Grenze", serif        /* títulos principales góticos (hero, shop title) */
```

**Regla:** Los títulos de sección grandes (`h1` de hero, tienda, etc.) usan `--font-gothic-title`. Encabezados secundarios usan `--font-heading`. El cuerpo siempre usa `--font-body`.

---

## CSS Naming Conventions

El tema usa **BEM** (`block__element--modifier`) con prefijos de contexto.

### Prefijos de bloque por área

| Prefijo | Área |
|---|---|
| `homepage-` | Secciones de front-page.php |
| `shop-archive__` | Tienda / archivo de productos |
| `shop-product-card__` | Tarjeta de producto en el loop |
| `site-header` / `header-` | Cabecera |
| `site-footer__` | Pie de página |
| `contact-` | Página de contacto |
| `page-` | Template genérico de página |
| `entry-` | Posts (template-parts/content) |

### Ejemplos representativos

```
.homepage-hero              → bloque
.homepage-hero__inner       → elemento
.homepage-hero--has-video   → modificador
.homepage-hero__content     → elemento
.homepage-kicker            → elemento reutilizable (label sobre títulos)
.homepage-shell             → contenedor/wrapper de ancho máximo (reutilizable en todas las secciones)
.homepage-button            → CTA primario
```

### `.homepage-shell`

Es el **contenedor de ancho máximo** reutilizable del tema. Se usa dentro de secciones de la homepage, shop archive, contact, etc. para centrar y limitar el contenido. Equivalente a un `.container`. **Siempre usar este wrapper** en lugar de crear uno nuevo.

---

## Estructura de archivos

```
nycteria-store/
├── style.css                  ← Estilos principales + variables CSS
├── woocommerce.css            ← Overrides de WooCommerce
├── functions.php              ← Setup, enqueues, includes
├── front-page.php             ← Página de inicio (hero + productos + marketing)
├── header.php                 ← Header con logo, nav, search toggle, cart icon
├── footer.php                 ← Footer 3-col: brand/nav/connect + newsletter + copyright
├── page.php                   ← Template de páginas con breadcrumbs
├── single.php                 ← Template de posts individuales
├── archive.php                ← Archivos de posts
├── search.php                 ← Resultados de búsqueda
├── 404.php                    ← Página de error 404
├── sidebar.php                ← Sidebar con widgets
├── comments.php               ← Template de comentarios
├── template-contact.php       ← Custom template: "Contact Page"
├── inc/
│   ├── customizer.php         ← Todas las opciones del Customizer
│   ├── woocommerce.php        ← WooCommerce setup, wrappers, cart fragments
│   ├── template-tags.php      ← nycteria_store_posted_on/by/entry_footer/post_thumbnail
│   ├── template-functions.php ← body_class, pingback header
│   ├── custom-header.php      ← Soporte de Custom Header de WP core
│   └── jetpack.php            ← Infinite scroll, responsive videos
├── woocommerce/
│   ├── archive-product.php    ← Shop page con hero + filtros de categoría + grid
│   └── content-product.php    ← Product card con badge "Nuevo" (< 30 días)
├── template-parts/
│   ├── content.php            ← Loop de posts
│   ├── content-page.php       ← Contenido de página
│   ├── content-search.php     ← Resultado de búsqueda
│   └── content-none.php       ← Estado vacío
├── js/
│   ├── navigation.js          ← Hamburger menu + keyboard/focus nav
│   └── customizer.js          ← Live preview en el Customizer
└── sass/                      ← Fuentes SCSS (compiladas a style.css)
```

---

## WooCommerce

### Soporte declarado

```php
add_theme_support( 'woocommerce', [
    'thumbnail_image_width' => 150,
    'single_image_width'    => 300,
    'product_grid'          => [
        'default_columns' => 4,
        'min_columns'     => 1,
        'max_columns'     => 6,
        'default_rows'    => 3,
        'min_rows'        => 1,
    ],
] );
add_theme_support( 'wc-product-gallery-zoom' );
add_theme_support( 'wc-product-gallery-lightbox' );
add_theme_support( 'wc-product-gallery-slider' );
```

- El CSS de WooCommerce por defecto está **desactivado** (`woocommerce_enqueue_styles` → array vacío). Todos los estilos viven en `woocommerce.css`.
- Los wrappers de WooCommerce (`woocommerce_before_main_content`) están reemplazados por los del tema.
- Fragmentos de carrito (AJAX) gestionados en `inc/woocommerce.php`.
- Productos relacionados: 3 columnas, 3 por página.

### Tarjeta de producto (`content-product.php`)

- Badge `"Nuevo"` automático si el producto fue publicado hace menos de 30 días
- Usa `shop-product-card` como bloque BEM
- Grid de 4 columnas en `ul.products` vía CSS Grid

---

## WordPress Customizer

Todas las opciones están en `inc/customizer.php`. Al agregar nuevas opciones, **seguir el patrón existente** de `add_setting` + `add_control` con `sanitize_callback`.

### Secciones

| ID | Título | Uso |
|---|---|---|
| `theme_options` | Theme Options | Show search toggle, announcement bar |
| `nycteria_homepage` | Homepage | Hero (imagen/video, título, subtítulo, CTA), bloque marketing |
| `nycteria_contact_page` | Contact Page | Hero, formulario (shortcode), horarios, teléfono, mapa embed |
| `nycteria_footer` | Footer | Imagen de marca, descripción, Instagram, Pinterest, newsletter, copyright |

### Settings clave

```
nycteria_header_announcement     → Texto en barra de anuncio bajo el header
nycteria_hero_background_image   → ID de adjunto (imagen de fondo del hero)
nycteria_hero_background_video   → URL de video MP4/WebM para el hero
nycteria_hero_title              → Título H1 del hero
nycteria_hero_subtitle           → Subtítulo del hero
nycteria_hero_cta_label          → Label del botón CTA
nycteria_hero_cta_url            → URL del botón CTA
nycteria_marketing_image         → Imagen del bloque de marketing
nycteria_marketing_kicker        → Kicker del bloque de marketing
nycteria_marketing_title         → Título del bloque de marketing
nycteria_marketing_copy          → Cuerpo del bloque de marketing
nycteria_contact_hero_title      → Título H1 de la página de contacto
nycteria_contact_hero_subtitle   → Subtítulo de la página de contacto
nycteria_contact_form_shortcode  → Shortcode del formulario de contacto
nycteria_contact_hours           → Horarios de atención (acepta HTML vía wpautop)
nycteria_contact_phone           → Teléfono de contacto (fallback al de WC)
nycteria_contact_map_embed_url   → URL embed de Google Maps
nycteria_footer_brand_image      → ID de adjunto (logo del footer)
nycteria_footer_brand_description → Descripción de la marca en el footer
nycteria_footer_instagram_url    → URL de Instagram
nycteria_footer_pinterest_url    → URL de Pinterest
nycteria_footer_newsletter_shortcode → Shortcode del newsletter
nycteria_footer_copyright_text  → Texto de copyright
```

---

## Menús de navegación

```php
'menu-1' => 'Primary'   // nav principal en el header
'footer' => 'Footer'    // nav en el footer
```

---

## Breadcrumbs

La página genérica (`page.php`) soporta tres fuentes en orden de prioridad:

1. **Yoast SEO** — `yoast_breadcrumb()`
2. **Rank Math** — `rank_math_the_breadcrumbs()`
3. **Fallback manual** — Home / Título de página

Usar siempre el mismo patrón condicional al agregar breadcrumbs en nuevos templates.

---

## Patrones de HTML reutilizables

### Kicker (label sobre títulos)
```html
<p class="homepage-kicker">Texto del kicker</p>
```

### Sección con contenedor
```html
<section class="[prefix]-section" aria-labelledby="[id]">
    <div class="homepage-shell">
        <div class="homepage-section__header">
            <p class="homepage-kicker">Kicker</p>
            <h2 id="[id]" class="homepage-section__title">Título</h2>
        </div>
        <!-- contenido -->
    </div>
</section>
```

### Botón CTA primario
```html
<a class="homepage-button" href="[url]">Label</a>
```

### Info card (usada en contact)
```html
<section class="contact-info-card">
    <h2 class="contact-info-card__title">
        <!-- SVG icon -->
        Título
    </h2>
    <div class="contact-info-card__content">
        <!-- contenido -->
    </div>
</section>
```

---

## Seguridad y buenas prácticas

- **Escapar siempre** la salida: `esc_html()`, `esc_url()`, `esc_attr()`, `wp_kses_post()`
- **Sanitizar** entradas del Customizer: `sanitize_text_field`, `esc_url_raw`, `absint` según el tipo
- **Nunca** guardar HTML arbitrario del usuario sin `wp_kses_post()`
- Los IDs de adjuntos se obtienen con `absint( get_theme_mod(...) )` antes de pasarlos a `wp_get_attachment_image()`
- URLs de video/embed se validan con `esc_url()`
- Usar `! defined( 'ABSPATH' )` al inicio de archivos incluidos directamente

---

## Reglas para nuevos features

### PHP / Templates

1. Nuevas funciones helper van en `inc/template-functions.php` o en un archivo nuevo bajo `inc/` (incluirlo desde `functions.php`)
2. Funciones de template tags van en `inc/template-tags.php`
3. Prefijo de función: `nycteria_store_`
4. Nuevas opciones del Customizer van en `inc/customizer.php` siguiendo el patrón `add_setting` → `add_control`
5. Nuevos templates de WooCommerce van en `woocommerce/` para respetar el override automático de WC
6. Custom page templates usan la convención `template-[nombre].php` con la cabecera `/* Template Name: ... */`

### CSS

1. Usar **siempre** las CSS Custom Properties del `:root` — nunca valores hardcodeados
2. Seguir BEM: `.bloque__elemento--modificador`
3. Usar el prefijo de área correspondiente (ver tabla §CSS Naming)
4. Reutilizar `.homepage-shell` como wrapper de sección en lugar de crear uno nuevo
5. El diseño es mobile-first — breakpoints clave: `48em` (tablet) y `64em` (desktop)
6. `font-size` y espaciados preferir las variables `--step-*` y `clamp()` para fluidez
7. Los estilos de WooCommerce van en `woocommerce.css`, no en `style.css`

### JS

1. Vanilla JS — no depender de jQuery salvo que sea necesario por integración con WP
2. Código nuevo en `js/` o en `assets/js/` si es más complejo
3. Enqueue desde `nycteria_store_scripts()` en `functions.php`

### Tono y copy

- Marca: **Nycteria Gothic Boutique** — elegante, oscura, editorial
- Idioma por defecto del front-end: **español** (el tema usa strings en ES)
- Evitar copy genérico — priorizar frases con carácter y atmósfera
- Kickers: cortos, en mayúsculas o pequeñas caps, descriptivos del contexto

---

## Plugins esperados (no requeridos salvo WooCommerce)

| Plugin | Uso en el tema |
|---|---|
| **WooCommerce** | Tienda completa, requerido |
| Yoast SEO | Breadcrumbs en `page.php` |
| Rank Math | Breadcrumbs alternativo |
| Jetpack | Infinite scroll, videos responsivos |
| Any contact form (CF7, WPForms…) | Shortcode en `nycteria_contact_form_shortcode` |
| Any newsletter (Mailchimp, etc.) | Shortcode en `nycteria_footer_newsletter_shortcode` |

---

## Checklist antes de hacer un PR / commit

- [ ] El código PHP escapa correctamente toda la salida
- [ ] Los nuevos settings del Customizer tienen `sanitize_callback`
- [ ] Las nuevas clases CSS siguen BEM y el prefijo de área correcto
- [ ] Se usan las CSS Custom Properties del `:root` (no valores hardcodeados)
- [ ] El copy refleja el tono gótico/editorial de la marca
- [ ] Los textos están envueltos en `__()` o `esc_html__()` con text domain `nycteria-store`
- [ ] No se introducen dependencias JS nuevas sin justificación
- [ ] Los archivos de WooCommerce van en `woocommerce/` para respetar el sistema de overrides
