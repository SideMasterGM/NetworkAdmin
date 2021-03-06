<?php
	include ("ssh.class.php");

	if ($service == "dhcp"){
		$CN = new ConnectSSH("192.168.100.2", "network", "123");
	} else if ($service == "dns"){
		$CN = new ConnectSSH("192.168.100.3", "network", "123");		
	} else if ($service == "web"){
		$CN = new ConnectSSH("192.168.100.4", "network", "123");
	} else if ($service == "more"){
		$CN = new ConnectSSH();
	}

	if (isset($service) && !$CN->connect){
		?>
			<script>
				alert("No se ha podido establecer la conexión con el equipo (<?php echo $CN->ip_host; ?>)");
			</script>
		<?php
	}

	function Fatality($ArrayEstado){
		$contadorSecond = 0;
		for ($i=0; $i < count($ArrayEstado); $i++) { 
			$Second = explode(PHP_EOL, $ArrayEstado[$i]);

			$contadorSecond = count($Second);
			return $contadorSecond;
		}
	}
?>

<div class="md-modal md-effect-1" id="modal-1">
	<div class="md-content">
		<h3 ondblclick="javascript: CloseModal();">Memoria | Discos Duros</h3>
		<?php 
			$ArrayContent 		= explode(",", $CN->getMemoryState()); 
			$ArrayContentTwo 	= explode(",", $CN->getDiskUsage()); 
			// $ArrayInterfaces = explode("=", $CN->getNetworkInterfaces()); 
			// echo $ArrayContent[0];
		?>

		<div>
			<p>Estado actual de la memoria</p>
			<table ondblclick="javascript: CloseModal();" style="width: 100%;">
				<tr>
					<td style="width: 33%;"><b>Memoria total</b></td>
					<td style="width: 33%;"><b>Memoria utilizada</b></td>
					<td style="width: 33%;"><b>Memoria libre</b></td>
				</tr>

				<tr>
					<td><?php echo $ArrayContent[0]."MB"; ?></td>
					<td><?php echo $ArrayContent[1]."MB"; ?></td>
					<td><?php echo $ArrayContent[2]."MB"; ?></td>
				</tr>
			</table>
			<br/>
			<table style="width: 100%;">
				<tr>
					<td style="width: 33%;"><b>Memoria compartida</b></td>
					<td style="width: 33%;"><b>Búfer/Caché</b></td>
					<td style="width: 33%;"><b>Memoria disponible</b></td>
				</tr>

				<tr>
					<td><?php echo $ArrayContent[3]."MB"; ?></td>
					<td><?php echo $ArrayContent[4]."MB"; ?></td>
					<td><?php echo $ArrayContent[5]."MB"; ?></td>
				</tr>
			</table>

			<br/>
			<p>Uso de Discos Duros</p>
			<table ondblclick="javascript: CloseModal();" style="width: 100%;">
				<tr>
					<td style="width: 30%;"><b>Tamaño del disco</b></td>
					<td style="width: 25%;"><b>Disco usado</b></td>
					<td style="width: 20%;"><b>Disponible</b></td>
					<td style="width: 25%;"><b>% en uso</b></td>
				</tr>

				<tr>
					<td><?php echo $ArrayContentTwo[0]; ?></td>
					<td><?php echo $ArrayContentTwo[1]; ?></td>
					<td><?php echo $ArrayContentTwo[2]; ?></td>
					<td><?php echo $ArrayContentTwo[3]; ?></td>
				</tr>
			</table>

			<button id="ClickMemoria" class="md-close">Cerrar</button>
		</div>
	</div>
</div>

<div class="md-modal md-effect-2" id="modal-2">
	<div class="md-content">
		<h3 ondblclick="javascript: CloseModal();">Interfaces de Red | IP</h3>
		<div>
			<?php
				$ArrayIntIP = explode(",", explode("=", $CN->getNetworkInterfaces())[1]);
			?>
			<p>Interfaces de red y direcciones IP colocadas en ella</p>
			<table ondblclick="javascript: CloseModal();" style="width: 100%;">
				<tr>
					<td style="width: 30%;"><b>Nombre de la interfaz</b></td>
					<td style="width: 25%;"><b>Dirección IP</b></td>
				</tr>

				
				<?php
					$i = 0;
					foreach ($ArrayIntIP as $value) {

						?>
							<tr>
								<td><?php echo $ArrayIntIP[$i++]; ?></td>
								<td><?php echo $ArrayIntIP[$i++]; ?></td>
							</tr>
						<?php
					}
				?>

			</table>
			<button class="md-close">Cerrar!</button>
		</div>
	</div>
</div>
<div class="md-modal md-effect-3" id="modal-3" style="top: 50%;">
	<div class="md-content">
		<h3 ondblclick="javascript: CloseModal();">Puertos</h3>
		<div>
			<?php
				$ArrayPuerto = explode(",", explode("=", $CN->getOpenPorts())[1]);
			?>
			<p>Puertos que se encuentran abiertos</p>
			<table ondblclick="javascript: CloseModal();" style="width: 100%;">
				<tr>
					<td style="width: 50%;"><b>Número de puerto</b></td>
					<td style="width: 50%;"><b>Protocolo</b></td>
				</tr>

				<?php
					for ($i=0; $i < count($ArrayPuerto); $i++) { 
						$Firts = explode(" ", $ArrayPuerto[$i]);

						for ($j=0; $j < count($Firts); $j++) { 
							?>
								<tr>
									<td><?php echo $Firts[$j]; ?></td>
									<td><?php echo $Firts[$j+1]; $j++; ?></td>
								</tr>
							<?php
						}
					}
				?>


			</table>
			<button class="md-close">Cerrar!</button>
		</div>
	</div>
</div>
<div class="md-modal md-effect-4" id="modal-4" style="top: 50%; width: 80%; max-width: 90%;">
	<div class="md-content">
		<h3 ondblclick="javascript: CloseModal();">Estado</h3>
		<div>
			<?php
				$ArrayEstado = explode("|", explode("=", $CN->getNetworkConnections())[0]);

			?>
			<p>Estados de las conexiones de red</p>
			<table ondblclick="javascript: CloseModal();" style="width: 100%;">
				<tr style="width: 100%;">
					<td><b>Protocolo</b></td>
					<td><b>Dirección Local</b></td>
					<td><b>Dirección Remota</b></td>
					<td><b>Estado</b></td>
					<td><b>Temporizador</b></td>
				</tr>

				<?php
					$contadorSecond = 0;
					for ($i=0; $i < count($ArrayEstado); $i++) { 
						$Second = explode(PHP_EOL, $ArrayEstado[$i]);

						$contadorSecond = count($Second);
						for ($j=0; $j < count($Second); $j++) { 
							$Cero[$i][$j] = $Second[$j];
						}
					}

					for ($i=0; $i < Fatality($ArrayEstado); $i++) { 
						?>
							<tr>
						<?php
						for ($j=0; $j < count($ArrayEstado); $j++) { 
							?>
								<td><?php echo $Cero[$j][$i]; ?></td>
							<?php
						}
						?>
							</tr>
						<?php
					}

				?>

			</table>
			<button class="md-close">Cerrar!</button>
		</div>
	</div>
</div>
<div class="md-modal md-effect-5" id="modal-5">
	<div class="md-content">
		<h3 ondblclick="javascript: CloseModal();">Usuarios</h3>
		<?php 
			$ArrayUsuario = explode(",", explode("=", $CN->getUsersConnected())[0]);
		?>

		<div>
			<p>Usuarios del sistema indicando cuales están logueados actualmente</p>
			<table ondblclick="javascript: CloseModal();" style="width: 100%;">
				<tr>
					<td style="width: 50%;"><b>Cantidad de usuarios</b></td>
					<td style="width: 50%;"><b>Nombre de usuario</b></td>
				</tr>

				<?php
					for ($i=0; $i < count($ArrayUsuario) - 1; $i++) { 
						?>
							<tr>
								<td><?php echo $i+1; ?></td>
								<td><?php echo $ArrayUsuario[$i]; ?></td>
							</tr>
						<?php
					}
				?>
			</table>
			<br>
			
			<button class="md-close">Cerrar</button>
		</div>
	</div>
</div>
<div class="md-modal md-effect-6" id="modal-6">
	<div class="md-content">
		<h3 ondblclick="javascript: CloseModal();">Monitor DHCP</h3>
		<div>
			<?php
				// echo $ArrayInterfaces[6];
				$ArrayDHCP = explode("|", explode("=", $CN->getDHCPShowAssignIP())[1]);
			?>
			<p>Interfaz en la que está asignando IP's y las asignaciones realizadas.</p>
			<table ondblclick="javascript: CloseModal();" style="width: 100%;">
				<tr style="width: 100%;">
					<td><b>Mes</b></td>
					<td><b>Dia</b></td>
					<td><b>Hora</b></td>
					<td><b>IP Asignada</b></td>
					<td><b>Host Cliente</b></td>
					<td><b>Interfaz</b></td>
				</tr>

				<?php
					$contadorSecond = 0;
					for ($i=0; $i < count($ArrayDHCP); $i++) { 
						$Second = explode(PHP_EOL, $ArrayDHCP[$i]);

						$contadorSecond = count($Second);
						for ($j=0; $j < count($Second); $j++) { 
							$Cero[$i][$j] = $Second[$j];
						}
					}

					for ($i=0; $i < Fatality($ArrayDHCP); $i++) { 
						?>
							<tr>
						<?php
						for ($j=0; $j < count($ArrayDHCP); $j++) { 
							?>
								<td><?php echo $Cero[$j][$i]; ?></td>
							<?php
						}
						?>
							</tr>
						<?php
					}

				?>

			</table>
			<button class="md-close">Cerrar!</button>
		</div>
	</div>
</div>
<div class="md-modal md-effect-7" id="modal-7" style="top: 10%; width: 70%; max-width: 70%;">
	<div class="md-content">
		<h3 ondblclick="javascript: CloseModal();">Configuración de Zonas</h3>
		<?php
			// echo $ArrayInterfaces[5];
			// $Valores = explode(PHP_EOL, $ArrayInterfaces[5]);
			$Valores = explode("\n", explode("=", $CN->getDNSFileZones())[0]);
		?>

		<div>
			<p>Archivos de zonas configuradas y las traducciones que estos contienen.</p>
			<table ondblclick="javascript: CloseModal();" style="width: 100%;">
				<tr>
					<td style="width: 25%;"><b>Fichero de zona</b></td>
					<td style="width: 25%;"><b>Dominio</b></td>
					<td style="width: 25%;"><b>Traducción</b></td>
					<td style="width: 25%;"><b>Dirección IP</b></td>
				</tr>

				<?php
					for ($i=0; $i < count($Valores); $i++) { 
	
						$new_value = explode(",", $Valores[$i]);					
						?>
							<tr>
						<?php
						for ($j=0; $j < count($new_value) ; $j++) { 
							?>
								<td><?php echo $new_value[$j]; ?></td>
							<?php
						}
						?>
							</tr>
						<?php
					}
				?>

			</table>
			<br>
			
			<button class="md-close">Cerrar</button>
		</div>
	</div>
</div>
<div class="md-modal md-effect-8" id="modal-8">
	<div class="md-content">
		<h3 ondblclick="javascript: CloseModal();">Sitios Virtuales</h3>
		<?php
			// echo $ArrayInterfaces[5];
			$ValVirtualHost = explode("\n", explode("=", $CN->getHTTPVirtualHost())[0]);
		?>

		<div>
			<p>Sitios virtuales configurados</p>
			<table ondblclick="javascript: CloseModal();" style="width: 100%;">
				<tr>
					<td style="width: 33%;"><b>VirtualHost</b></td>
					<td style="width: 33%;"><b>Nombre de dominio</b></td>
					<td style="width: 33%;"><b>Estado</b></td>
				</tr>

				<?php
					for ($i=0; $i < count($ValVirtualHost); $i++) { 
	
						$value_VH = explode(",", $ValVirtualHost[$i]);

						if ($value_VH[0] == "default-ssl.conf"){
							?>
								<tr>
							<?php
							for ($j=0; $j < count($value_VH) ; $j++) { 
								?>
									<td style="color: #2d2d2d;"><?php echo $value_VH[$j]; ?></td>
								<?php
							}
							?>
								</tr>
							<?php
						} else {
							?>
								<tr>
							<?php
							for ($j=0; $j < count($value_VH) ; $j++) { 
								?>
									<td><?php echo $value_VH[$j]; ?></td>
								<?php
							}
							?>
								</tr>
							<?php
						}

					}
				?>

			</table>
			<br>
			
			<button class="md-close">Cerrar</button>
		</div>
	</div>
</div>

<div class="md-modal md-effect-19" id="modal-19">
	<div class="md-content">
		<h3 ondblclick="javascript: CloseModal();">Discos Duros</h3>
		<div>
			<p>Uso de Discos Duros</p>
			<table ondblclick="javascript: CloseModal();" style="width: 100%;">
				<tr>
					<td style="width: 30%;"><b>Tamaño del disco</b></td>
					<td style="width: 25%;"><b>Disco usado</b></td>
					<td style="width: 20%;"><b>Disponible</b></td>
					<td style="width: 25%;"><b>% en uso</b></td>
				</tr>

				<tr>
					<td><?php echo $ArrayContent[6]; ?></td>
					<td><?php echo $ArrayContent[7]; ?></td>
					<td><?php echo $ArrayContent[8]; ?></td>
					<td><?php echo $ArrayContent[9]; ?></td>
				</tr>
			</table>
			<button class="md-close">Cerrar!</button>
		</div>
	</div>
</div>

<div class="md-modal md-effect-9" id="modal-9">
	<div class="md-content">
		<h3 class="DobleClickGarantizado" ondblclick="javascript: CloseModal();">Nuevo Host</h3>
		<div>
			<p>Se agrega un nuevo host para monitorizarlo mediante este panel de control.</p>
			<table ondblclick="javascript: CloseModal();" style="width: 100%;">
				
				<tr>
					<td style="width: 33%;"><b>Dirección IP</b></td>
					<td style="width: 33%;"><b>Nombre de usuario</b></td>
					<td style="width: 33%;"><b>Contraseña</b></td>
				</tr>
				<tr>
					<td style="width: 32%;"><input type="text" id="chg_ip_address" placeholder="IP Address" /></td>
					<td style="width: 32%;"><input type="text" id="chg_username" placeholder="Username" /></td>
					<td style="width: 32%;"><input type="password" id="chg_password" placeholder="Password" /></td>
				</tr>
			</table>
			<br><br>
			<input type="hidden" class="md-close" id="ClickCloseNow"/>
			<button id="ClickAddHost" onclick="javascript: SendDataNewHost();">¡Listo, Conectar!</button>
		</div>
	</div>
</div>

<div class="md-modal md-effect-18" id="modal-18">
	<div class="md-content">
		<h3 ondblclick="javascript: CloseModal();">Copias de Seguridad | Backup</h3>
		<div>
			<p>Realizar copias de seguridad de los servicios instalados en los hosts remotos.</p>
			<table ondblclick="javascript: CloseModal();" style="width: 100%;">
				<form id="FormCheckServices">
					<input type="hidden" id="text_CS_IPAddress" name="text_CS_IPAddress" />
					<input type="hidden" id="text_CS_Username" name="text_CS_Username" />
					<tr>
						<td style="width: 30%;"><label><input type="checkbox" onchange="javascript: ChangeValueCheckBox(this);" name="check_apache" id="check_apache" value="no"> APACHE</label></td>
						<td style="width: 30%;"><label><input type="checkbox" onchange="javascript: ChangeValueCheckBox(this);" name="check_mysql" id="check_mysql" value="no"> MYSQL</label></td>
						<td style="width: 30%;"><label><input type="checkbox" onchange="javascript: CheckDirData(this);" id="check_data" value="no"> DATOS</label></td>
					</tr>

					<tr>
						<td style="width: 30%;"><label><input type="checkbox" onchange="javascript: ChangeValueCheckBox(this);" name="check_dns" id="check_dns" value="no"> DNS</label></td>
						<td style="width: 30%;"><label><input type="checkbox" onchange="javascript: ChangeValueCheckBox(this);" name="check_dhcp" id="check_dhcp" value="no"> DHCP</label></td>
						<td style="width: 30%;"><input type="text" name="path_dirdata" id="path_dirdata" placeholder="/Ruta /del /directorio" disabled /></td>
					</tr>
				</form>

			</table>
			<br>
			<button class="md-close" style="float: left;" id="ClickCloseNowExecServices">Cerrar!</button>
			<button id="BtnExecServices" style="float: right;" onclick="javascript: ExecServices();">¡Realizar Backup!</button>
		</div>
	</div>
</div>

<form id="FormAddNewHost">
	<input type="hidden" id="add_ip_address" name="add_ip_address" />
	<input type="hidden" id="add_username" name="add_username" />
	<input type="hidden" id="add_password" name="add_password" />
</form>

<div class="md-modal md-effect-11" id="modal-11">
	<div class="md-content WriteTrackingNetwork">
		
	</div>
	<button id="ClickTrackingNetwork" class="md-close">Cerrar</button>
</div>

<div class="md-modal md-effect-13" id="modal-13">
	<div class="md-content WriteMemoriaDiscos">
		
	</div>
	<button id="ClickMemoriaDiscos" class="md-close">Cerrar</button>
</div>

<div class="md-modal md-effect-14" id="modal-14">
	<div class="md-content WriteInterfaces">
		
	</div>
	<button id="ClickInterfaces" class="md-close">Cerrar</button>
</div>

<div class="md-modal md-effect-15" id="modal-15">
	<div class="md-content WritePuertos">
		
	</div>
	<button id="ClickPuertos" class="md-close">Cerrar</button>
</div>

<div class="md-modal md-effect-16" id="modal-16">
	<div class="md-content WriteEstado">
		
	</div>
	<button id="ClickEstado" class="md-close">Cerrar</button>
</div>

<div class="md-modal md-effect-17" id="modal-17">
	<div class="md-content WriteUsuarios">
		
	</div>
	<button id="ClickUsuarios" class="md-close">Cerrar</button>
</div>

<div class="md-modal2 md-effect-10" id="modal-10">
	<div class="md-content WriteNewModal">
	</div>
	<button id="ClickNewModal" class="md-close">Cerrar</button>
</div>