FROM node:24.13.1

WORKDIR /app

CMD ["npm","run","dev","--","--host"]

#Para desarrollo, se puede usar el siguiente Dockerfile que mantiene el contenedor en ejecución sin ejecutar la aplicación
#Esto es util para crear el proyecto por primera vez, instalar dependencias, etc. Luego se puede cambiar el CMD para ejecutar la aplicación normalmente.
#Si modificas el CMD para ejecutar la aplicación, recuerda que debes reconstruir la imagen y reiniciar el contenedor para que los cambios tengan efecto.
#FROM node:24.13.1
#WORKDIR /app
#CMD ["tail", "-f", "/dev/null"]
