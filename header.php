<?php

/**
 * Header fijo del sitio con gradiente y animación de scroll (GSAP ScrollTrigger).
 * Se incluye en todas las páginas del proyecto.
 */
?>

<!-- Tipografía Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">

<!-- CSS del header -->
<link rel="stylesheet" href="css/header.css">

<!-- GSAP + ScrollTrigger -->
<script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>

<header class="main-tool-bar" id="mainHeader">

	<!-- Logo alineado a la izquierda -->
	<div class="header-logo">
		<img src="img/IECM-1.png" alt="IECM">
	</div>

	<!-- Título centrado -->
	<div
		class="header-title-group"
		style="display:flex; flex:1; flex-direction:column; align-items:center; justify-content:center; text-align:center; padding:0 16px;"
	>
		<h1
			class="header-title-group__main"
			style="margin:0;"
		>
			Concurso Juvenil de Ensayo 2026
		</h1>
		<!-- <div
			class="header-title-group__divider"
			style="width:40px; height:2px; margin:4px auto; border-radius:2px; background-color:#4A9FD5;"
		></div> -->
		<h2
			class="header-title-group__subtitle"
			style="margin:0;"
		>
			&mdash; Conversando con los clásicos &mdash;
		</h2>
	</div>

	<!-- Panel de usuario (visible sólo si hay sesión activa) -->
	<div class="header-user">
		<?php if (!empty($_SESSION['idusuario'])): ?>
			<div class="header-user__chip">
				<div class="header-user__avatar">
					<i class="fas fa-user"></i>
				</div>
				<span class="header-user__name">
					<?php echo htmlspecialchars($_SESSION['usr'] ?? $my_user ?? 'Usuario'); ?>
				</span>
			</div>
			<a href="logout.php" class="header-user__logout">
				<i class="fas fa-sign-out-alt"></i>
				<span class="header-user__logout-text">Cerrar sesión</span>
			</a>
		<?php elseif (isset($show_back_button) && $show_back_button): ?>
			<a href="inicio.php" class="header-back-button">
				<i class="fas fa-arrow-left"></i>
				<span class="header-back-button__text">Volver al inicio</span>
			</a>
		<?php else: ?>
			<div style="flex-shrink:0; width:160px;"></div>
		<?php endif; ?>
	</div>

	<!-- Botón hamburguesa (solo pantallas pequeñas) -->
	<?php if (!empty($_SESSION['idusuario']) || (isset($show_back_button) && $show_back_button)): ?>
	<button class="header-hamburger" id="headerHamburger" aria-label="Menú">
		<span></span><span></span><span></span>
	</button>
	<?php endif; ?>

</header>

<!-- Menú móvil desplegable -->
<?php if (!empty($_SESSION['idusuario'])): ?>
<div class="header-mobile-menu" id="headerMobileMenu">
	<div class="header-mobile-menu__chip">
		<div class="header-user__avatar">
			<i class="fas fa-user"></i>
		</div>
		<span><?php echo htmlspecialchars($_SESSION['usr'] ?? $my_user ?? 'Usuario'); ?></span>
	</div>
	<a href="logout.php" class="header-mobile-menu__logout">
		<i class="fas fa-sign-out-alt"></i> Cerrar sesión
	</a>
</div>
<?php elseif (isset($show_back_button) && $show_back_button): ?>
<div class="header-mobile-menu" id="headerMobileMenu">
	<a href="inicio.php" class="header-mobile-back-button">
		<i class="fas fa-arrow-left"></i> Volver al inicio
	</a>
</div>
<?php endif; ?>

<!-- Espaciador para que el contenido no quede bajo el header fijo -->
<div class="header-spacer"></div>

<script>
	gsap.registerPlugin(ScrollTrigger);

	// Oculta el header al bajar la página, lo muestra al subir
	const showAnim = gsap.from('#mainHeader', {
		yPercent: -100,
		paused: true,
		duration: 0.25
	}).progress(1);

	ScrollTrigger.create({
		start: 'top top',
		end: 'max',
		onUpdate: function(self) {
			if (self.direction === -1) {
				showAnim.play();
				document.getElementById('mainHeader').classList.remove('main-tool-bar--scrolled');
			} else {
				showAnim.reverse();
				document.getElementById('mainHeader').classList.add('main-tool-bar--scrolled');
			}
		}
	});

	// Hamburguesa
	const hamburger = document.getElementById('headerHamburger');
	const mobileMenu = document.getElementById('headerMobileMenu');
	if (hamburger && mobileMenu) {
		hamburger.addEventListener('click', function () {
			hamburger.classList.toggle('is-open');
			mobileMenu.classList.toggle('is-open');
		});
		// Cerrar al hacer click fuera
		document.addEventListener('click', function (e) {
			if (!hamburger.contains(e.target) && !mobileMenu.contains(e.target)) {
				hamburger.classList.remove('is-open');
				mobileMenu.classList.remove('is-open');
			}
		});
	}
</script>