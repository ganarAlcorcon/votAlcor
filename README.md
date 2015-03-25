# votAlcor
Proyecto web para los procesos de votación

Para que funcione, importa la estructura del SQL de recursos y añade un usuario en la tabla de simpatizantes.
Después modifica el fichero de web/lib/config.ini.dist para ajustar los parámetros a tu base de datos y quita el .dist

Si quieres acceder a la parte de administración, crea el permiso ADMIN en la tabla de permisos y añadelo a tu usuario en la tabla PERM_SIMP.