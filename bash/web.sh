#!/bin/bash

#----------	Estado actual de la MEMORIA ------------

function estadoMemoria() {
	MEMORIA=($(free -m | grep Memoria | cut -d ':' -f2))

	echo -e "\nEstado actual de la memoria"
	echo "-----------------------------------"
	echo "Memoria total: ${MEMORIA[0]}"
	echo "Memoria usada: ${MEMORIA[1]}"
	echo "Memoria libre: ${MEMORIA[2]}"
	echo "Memoria compartida: ${MEMORIA[3]}"
	echo "Búfer/caché : ${MEMORIA[4]}"
	echo "Memoria disponible: ${MEMORIA[5]}"
	echo "-----------------------------------"	
}

#--------- Uso de los discos duros ---------------

function usoDiscosDuros() {
	DISCO=($(df -PH | grep sda | cut -d '/' -f3))

	echo -e "\nUso del disco duro"
	echo "-----------------------------------"
	echo "Tamaño del disco: ${DISCO[1]}"
	echo "Disco usado: ${DISCO[2]}"
	echo "Disponible: ${DISCO[3]}"
	echo "Porcentaje de uso: ${DISCO[4]}"
	echo "-----------------------------------"
}

#-------- Interfaces de red y direcciones ip colocadas en ellas ---------

function interfacesRed() {
	#INTERFACES=($(ifconfig -a | grep mtu | cut -d ':' -f1))
	INTERFACES=($(ifconfig -a -s | awk {'print $1'}))
	echo -e "\nInterfaces de red y sus direcciones IP"
	echo "---------------------------------------"
	NUM_INTER=${#INTERFACES[*]}
	for (( i = 1; i < $NUM_INTER ; i++ )); do
		DIRECCION_IP=$(ifconfig ${INTERFACES[$i]} | grep 'inet ' | cut -d ' ' -f10)
		if [[ $DIRECCION_IP != "" ]]; then
			echo "${INTERFACES[$i]} $DIRECCION_IP"
		else
			echo "${INTERFACES[$i]} No tiene ip asignada"
		fi

	done
	echo "---------------------------------------"
}

#--------- Puertos que se encuentran abiertos -----------

function puertosAbiertos() {
	PORT_TCP=($(netstat -pltona | grep 'tcp ' | awk {'print $4'} | cut -d ':' -f2))
	PORT_TCP6=($(netstat -pltona | grep 'tcp6' | awk {'print $4'} | cut -d ':' -f4))
	echo -e "\nPuertos abiertos TCP"
	echo "------------------------------"
	echo ${PORT_TCP[*]}
	echo ${PORT_TCP6[*]}

	echo -e "\nPuertos abiertos UDP"
	PORT_UDP=($(netstat -pluona | grep 'udp ' | awk {'print $4'} | cut -d ':' -f2))
	PORT_UDP6=($(netstat -pluona | grep 'udp ' | awk {'print $4'} | cut -d ':' -f4))
	echo ${PORT_UDP[*]}
	echo ${PORT_UDP6[*]}
}

#----------Estado de las conexiones de red------------

function statusConectionsNetwork() {
	echo -e "\nEstado de las conexions de red"
	echo "------------------------------------"
	netstat -putona | grep -e udp -e tcp
}

#--------- Usuarios del sistema indicando cuales estan logueados actualmente -----------

function usuariosConectados() {
	USUARIOS=($(who | cut -d ' ' -f1))
	echo -e "\nUsuarios logueados actualmente"
	echo "---------------------------------------"
	echo "Número de usuarios conectados: ${#USUARIOS[*]}"
	for i in ${USUARIOS[*]}; do
		echo $i
	done
}

#Servidor DHCP: Interfaces e las que está asignado IPs y las asignaciones realizadas

function asignacionesRealizadas() {
	echo -e "\ninterfaces en las que está asignado IP y las asignacionese realizadas"
	echo "------------------------------------------------------------------------"
	STATUS_DHCP=$(service isc-dhcp-server status | tail -n10 | grep 'DHCPACK' | awk {'$4="";$5="";$6="";$7="";print'})
	echo "${STATUS_DHCP[*]}"
}

# Definición de funciones
estadoMemoria
usoDiscosDuros
interfacesRed
usuariosConectados
puertosAbiertos
statusConectionsNetwork
asignacionesRealizadas