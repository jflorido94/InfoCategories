# Info Categories

> Modulo realizado para Tecinet con la función de colocar un texto en la cabecera de las categorías seleccionadas, editable desde el backoffice de Prestashop.

## Instrucciones de uso

| Campos | Tipo  | Uso | Ejemplo |
|:--:|:--:|:--:|:--:|
| categorias | string | Categorías seleccionadas separadas por comas (,) | 3,5,356 |
|texto | string | Texto que se mostrará en la cabecera de las categorías (puede ser código HTML) | `<h1> Texto de prueba </h1>` |
| movil | boolean | Opción de mostrar el texto en dispositivos móviles |false |


## Modo de instalación

* Creamos el *.zip* del módulo, por ejemplo así se haría usando __WinRAR__:
	1. Hacemos click derecho sobre la carpeta __*InfoCategories*__	.
	2. Seleccionamos la opción __*Añadir al archivo*__.
	3. Dejamos todo igual, solo cambiamos el *Formato del archivo* a __*ZIP*__ y le damos *Aceptar*.

* En el backoffice de la página de __Prestashop__ en que queramos instalar este modulo hacemos los siguientes pasos:
	1. En el panel lateral izquierdo vamos a __*Módulos*__ y seleccionamos __*Gestor de módulo*__.
	2. Clicamos en el botón superior  __*Subir un módulo*__.
	3. Arrastramos o seleccionamos el archivo *.zip* antes creado y esperamos.
	4. Luego entramos en la configuración del módulo y rellenamos los campos de este modulo y luego guardamos.

* En la página de la tienda podremos ver el texto o HTML que hemos configurado anteriormente y en las categorías seleccionadas.
> __Importante__ Por favor no modificar nombres de archivos ni código dentro de los archivos, sin saber lo que está realizando y sin entender como se hacen módulos en Prestashop. Podría dejar de funcionar.