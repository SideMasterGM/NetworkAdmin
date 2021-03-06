<!DOCTYPE html>
<html lang="en" class="no-js">
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
		<title>Panel de Administración</title>
		<meta name="author" content="Jerson M. y Frankier F." />
		<link rel="stylesheet" type="text/css" href="css/normalize.css" />
		<link rel="stylesheet" type="text/css" href="css/demo.css" />
		<link rel="stylesheet" type="text/css" href="css/component2.css" />
		<script src="js/jquery.min.js"></script>
		<script src="js/jquery-ui.js"></script>
		<script src="js/modal.js"></script>
		<script src="js/modernizr-2.6.2.min.js"></script>
		<script src="js/script.js"></script>

		<link rel="stylesheet" type="text/css" href="css/component.css" />
		<script src="js/modernizr.custom.js"></script>
		
	</head>
	<body id="clicModal">
		
		<div class="container">
			
			<div class="component">
				<h2>Servidores</h2>
				
				<?php
					if (isset($_POST['service']) && !empty($_POST['service'])){
						$service = $_POST['service'];

						if ($service == "dhcp"){
							$Content = file_get_contents("bash/dhcp.txt");
						} else if ($service == "dns"){
							$Content = file_get_contents("bash/dns.txt");
						} else if ($service == "web"){
							$Content = file_get_contents("bash/web.txt");
						}

						?>
							<div class="cn-button" id="cn-button">
								<img src="src/host.png" style="width: 100px; margin: 3px 4px;" alt="Syslog" />
							</div>
							<!-- <button class="cn-button" id="cn-button">Menú</button> -->
							<input type="hidden" id="ClickModalTen" class="md-trigger" data-modal="modal-10" value="Nombre" />
							<div class="cn-wrapper" id="cn-wrapper">
								<ul style="margin-left: 120px;">
									<li class="md-trigger" data-modal="modal-1"><a><span>Memoria</span></a></li>
									<li class="md-trigger md-setperspective" data-modal="modal-19"><a><span>Discos</span></a></li>
									<li class="md-trigger" data-modal="modal-2"><a><span>Interfaces</span></a></li>
									<li class="md-trigger" data-modal="modal-3"><a><span>Puertos</span></a></li>
									<li class="md-trigger" data-modal="modal-4"><a><span>Estado</span></a></li>
									<li class="md-trigger" data-modal="modal-5"><a><span>Usuarios</span></a></li>
									
									<?php
										if (@$service == "dhcp"){
											?>
												<!-- <input type="hidden" name="nombre_servicio" id="nombre_servicio" value="dhcp" /> -->
												<li class="md-trigger" data-modal="modal-6"><a><span>Asignación</span></a></li>
											<?php
										} else if (@$service == "dns"){
											?>
												<!-- <input type="hidden" name="nombre_servicio" id="nombre_servicio" value="dns" /> -->
												<li class="md-trigger" data-modal="modal-7"><a><span>Zonas</span></a></li>
											<?php
										} else if (@$service == "web"){
											?>
												<!-- <input type="hidden" name="nombre_servicio" id="nombre_servicio" value="web" /> -->
												<li class="md-trigger" data-modal="modal-8"><a><span>Sitios</span></a></li>
											<?php
										}
									?>	
								 </ul>
							</div>

							<div class="cn2-button" id="cn2-button">
								<img src="src/log.png" class="img_syslog" alt="Syslog" />
							</div>
							<!-- <button class="cn2-button" id="cn2-button">SysLog</button> -->
							<div class="cn2-wrapper" id="cn2-wrapper">
								<ul style="margin-right: 120px;">
									<li class="md-trigger"><a style="color: #fc7d00;" onclick="javascript: LoadInfo('<?php echo $service; ?>', 'alert');"><span>Alerta</span></a></li>
									<li class="md-trigger md-setperspective"><a style="color: #fc9700;" onclick="javascript: LoadInfo('<?php echo $service; ?>', 'crit');"><span>Crítico</span></a></li>
									<li class="md-trigger"><a style="color: #fccd00;" onclick="javascript: LoadInfo('<?php echo $service; ?>', 'err');"><span>Error</span></a></li>
									<li class="md-trigger"><a style="color: #dbfc00;" onclick="javascript: LoadInfo('<?php echo $service; ?>', 'warning');"><span>Advert...</span></a></li>
									<li class="md-trigger"><a style="color: #00fc38;" onclick="javascript: LoadInfo('<?php echo $service; ?>', 'notice');"><span>Noticia</span></a></li>
									<li class="md-trigger"><a style="color: #00fcd0;" onclick="javascript: LoadInfo('<?php echo $service; ?>', 'info');"><span>Info...</span></a></li>
									<li class="md-trigger"><a style="color: #00dbff;" onclick="javascript: LoadInfo('<?php echo $service; ?>', 'debug');"><span>Debug</span></a></li>
								 </ul>
							</div>
						<?php
					}
				?>
			</div>
			<header>
				<h1>Monitorización de Servidores <span>Recolectar información de red para cada equipo conectado | SysLog en acción</span></h1>	
				<nav class="codrops-demos">
					<?php
						if (@$service == "dhcp"){
							?>
								<a class="current-demo class_dhcp" style="cursor: pointer;" onclick="javascript: start_service('dhcp')">DHCP</a>
							<?php
						} else {
							?>
								<a class="class_dhcp" style="cursor: pointer;" onclick="javascript: start_service('dhcp')">DHCP</a>
							<?php							
						}

						if (@$service == "dns"){
							?>
								<a class="current-demo class_dns" style="cursor: pointer;" onclick="javascript: start_service('dns')">DNS</a>
							<?php
						} else {
							?>
								<a class="class_dns" style="cursor: pointer;" onclick="javascript: start_service('dns')">DNS</a>
							<?php							
						}

						if (@$service == "web"){
							?>
								<a class="current-demo class_web" style="cursor: pointer;" onclick="javascript: start_service('web')">WEB</a>
							<?php
						} else {
							?>
								<a class="class_web" style="cursor: pointer;" onclick="javascript: start_service('web')">WEB</a>
							<?php
						}
					?>

					<form id="FormLoadData">
						<input type="hidden" name="service_name" id="service_name" />
						<input type="hidden" name="severity_level" id="severity_level" />
					</form>

					<form action="./" id="FormService" method="post">
						<input type="hidden" name="service" id="service" />
					</form>
				</nav>
			</header>
			
			<div class="windows_modals">
				<?php include ("php/modal.php"); ?>
			</div>


		</div>

		<div class="md-overlay"></div><!-- the overlay element -->

		<script src="js/classie.js"></script>
		<script src="js/modalEffects.js"></script>

		<!-- for the blur effect -->
		<!-- by @derSchepp https://github.com/Schepp/CSS-Filters-Polyfill -->
		<script>
			// this is important for IEs
			var polyfilter_scriptpath = '/js/';
		</script>
		<script src="js/cssParser.js"></script>
		<script src="js/css-filters-polyfill.js"></script>

		<script src="js/polyfills.js"></script>
		<script src="js/demo2.js"></script>
	</body>
</html>