#!/usr/bin/env bash

##################################################################
# Dirección IP, nombre del fichero y nombre de usuario
IPAddress=$1
FileName=$2
username="network"
##################################################################

# Ruta absoluta donde se encuentran todos los respaldos
PathAbsolute="Backups"
DirApache="apache/"
DirMySQL="mysql/"
ValorApache=0
ValorMySQL=0
##################################################################

function RestoreApache(){
	ListFiles=$(ls -R -f /Backups/ | grep ".tar.gz")

	for i in ${ListFiles[*]}; do 
		if [ $i == $FileName ]; then
			# echo "Encontrado: " $i

			IN=$i
			arrIN=(${IN//_/ })

			# for x in ${arrIN[*]}; do 
			# 	echo "Cadena: " $x
			# done

			local Day=`expr substr ${arrIN[1]} 1 2`
			local Mon=`expr substr ${arrIN[1]} 3 2`
			local Yea=`expr substr ${arrIN[1]} 5 4`
			local hrm=`expr substr ${arrIN[2]} 1 4`

			local DirDescompress=${arrIN[0]}"_"${arrIN[1]}"_"$hrm

			# local DirMonth
			local DirMonth=$(getMonth $Mon)

			DirName=$Day"-"$DirMonth"-"$Yea

			DirStorage=$(printf "/%s/%s" $PathAbsolute $DirName)
			cd $DirStorage
			# tar -xzvf $ruta_file $dir_dhcp -C .
			
			ListFilesCompress=$(tar -tzf $FileName)

			for y in ${ListFilesCompress[*]}; do
				if [ $y == $DirApache ]; then
					# echo "Directorio Apache encontrado"
					[ ! -d $DirDescompress/ ] && mkdir -p $DirDescompress/
					chmod -R 777 /$PathAbsolute/
					# pwd
					tar -xzvf $FileName $DirApache -C .
					mv ./$DirApache ./$DirDescompress/
					# exit
					# cd $DirDescompress
					# 
					# 
					chmod -R 777 $DirDescompress
					cd $DirDescompress/$DirApache
					scp -C -r etc/ $username@$IPAddress:/etc
					scp -C -r lib/ $username@$IPAddress:/usr/lib
					scp -C -r sbin/ $username@$IPAddress:/usr/sbin
					scp -C -r share/ $username@$IPAddress:/usr/share

					if [[ $? -eq 0 ]]; then
    					echo -e "\n\e[0;32Apache ha sido restaurado con éxito\n"
    					echo -e "\e[0;37m"
			        else
			            echo -e "\n\e[0;31mHa ocurrido un problema al intentar restaurar el servicio Apache\n"
			            echo -e "\e[0;37m"
			        fi

			        cd ../../
			        rm -rf $DirDescompress/

			        ValorApache=1
			        break
				else
					ValorApache=0
				fi
			done

			if [ $ValorApache == 0 ]; then
				ShowErrors apache2 "El servicio no existe"
			fi

		fi
	done
}

##################################################################
function RestoreMySQL(){
	ListFiles=$(ls -R -f /Backups/ | grep ".tar.gz")

	for i in ${ListFiles[*]}; do 
		if [ $i == $FileName ]; then
			# echo "Encontrado: " $i

			IN=$i
			arrIN=(${IN//_/ })

			# for x in ${arrIN[*]}; do 
			# 	echo "Cadena: " $x
			# done

			local Day=`expr substr ${arrIN[1]} 1 2`
			local Mon=`expr substr ${arrIN[1]} 3 2`
			local Yea=`expr substr ${arrIN[1]} 5 4`
			local hrm=`expr substr ${arrIN[2]} 1 4`

			local DirDescompress=${arrIN[0]}"_"${arrIN[1]}"_"$hrm

			# local DirMonth

			local DirMonth=$(getMonth $Mon)

			DirName=$Day"-"$DirMonth"-"$Yea

			DirStorage=$(printf "/%s/%s" $PathAbsolute $DirName)
			cd $DirStorage
			# tar -xzvf $ruta_file $dir_dhcp -C .
			
			ListFilesCompress=$(tar -tzf $FileName)

			for y in ${ListFilesCompress[*]}; do
				if [ $y == $DirMySQL ]; then
					# echo "Directorio MySQL encontrado"
					[ ! -d $DirDescompress/ ] && mkdir -p $DirDescompress/
					chmod -R 777 /$PathAbsolute/
					# pwd
					tar -xzvf $FileName $DirMySQL -C .
					mv ./$DirMySQL ./$DirDescompress/
					# exit
					# cd $DirDescompress
					# 
					# 
					chmod -R 777 $DirDescompress
					cd $DirDescompress/$DirMySQL
					scp -C -r etc/ $username@$IPAddress:/etc
					scp -C -r lib/ $username@$IPAddress:/usr/lib
					scp -C -r share/ $username@$IPAddress:/usr/share
					scp -C -r bin/ $username@$IPAddress:/usr/bin

					if [[ $? -eq 0 ]]; then
    					echo -e "\n\e[0;32Apache ha sido restaurado con éxito\n"
    					echo -e "\e[0;37m"
			        else
			            echo -e "\n\e[0;31mHa ocurrido un problema al intentar restaurar el servicio Apache\n"
			            echo -e "\e[0;37m"
			        fi

			        cd ../../
			        rm -rf $DirDescompress/

			        ValorMySQL=1
			        break
			    else
			    	ValorMySQL=0
				fi
			done

			if [ $ValorMySQL == 0 ]; then
				ShowErrors mysql "El servicio no existe"
			fi

		fi
	done
}

##################################################################
function getMonth(){
	case ${1} in
	    01)
	        echo "ene"
	    ;;
	    02)
	        echo "feb"
	    ;;
	    03)
	        echo "mar"
	    ;; 
	    04)
	        echo "abr"
	    ;; 
	    05)
	        echo "may"
	    ;; 
	    06)
	        echo "jun"
	    ;; 
	    07)
	        echo "jul"
	    ;; 
	    08)
	        echo "ago"
	    ;; 
	    09)
	        echo "sep"
	    ;; 
	    10)
	        echo "oct"
	    ;; 
	    11)
	        echo "nov"
	    ;;
	    12)
	        echo "dic"
	    ;;
	    *)
	        echo "null"
	    ;;
	esac
}
##################################################################
#Muestra los errores que ocurran, recibe parámetros con el servicio y mensaje
function ShowErrors(){
	echo -e "+---------------------------------------------------------+"
	echo -e "+ Script en ejecución:\e[0;31m" $0 "\e[0;37m| Servicio:\e[0;31m" ${1} "\e[0;37m"
	echo -e "+---------------------------------------------------------+"
	echo -e "+ Mensaje de Error:\e[0;31m" ${2} "\e[0;37m"
	echo -e "+---------------------------------------------------------+"
}

##################################################################
#Se llaman las funciones
RestoreApache
RestoreMySQL