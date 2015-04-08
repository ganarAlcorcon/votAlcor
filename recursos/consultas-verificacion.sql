
-- Número de registros por cookie (revisar los que tengan más)
SELECT COUNT(*) AS NUM_REGISTRADOS, `COOKIE` FROM `SIMPATIZANTES` GROUP BY `COOKIE` ORDER BY 1 DESC;

SELECT * FROM `SIMPATIZANTES` WHERE COOKIE='';


-- Número de registros por IP (revisar los que tengan más)
SELECT COUNT(*) AS NUM_REGISTRADOS, `IP_REGISTRO` FROM `SIMPATIZANTES` GROUP BY `IP_REGISTRO` ORDER BY 1 DESC;

SELECT * FROM `SIMPATIZANTES` WHERE IP_REGISTRO='';


-- Mismos nombres (por las mayúsculas)
SELECT COUNT(*) AS NUM_REGISTRADOS, upper(concat(`NOMBRE`, ' ',`APELLIDO1`, ' ', coalesce(`APELLIDO2`,''))) FROM `SIMPATIZANTES` GROUP BY upper(concat(`NOMBRE`,`APELLIDO1`, coalesce(`APELLIDO2`,''))) ORDER BY 1 DESC;


-- Posibles intentos de ocultar la cookie (puede ser simplemente misma conexión y distintos ordenadores, depende de cantidad)
SELECT S1.* FROM `SIMPATIZANTES` S1, `SIMPATIZANTES` S2 WHERE S1.ID != S2.ID AND S1.COOKIE != S2.COOKIE AND S1.IP_REGISTRO=S2.IP_REGISTRO;


-- Pedir que validen el email
SELECT * FROM `SIMPATIZANTES` WHERE EMAIL_V != 'S';

-- Si no lo han recibido, éstas son sus claves (si la clave aparece a NULL ha ocurrido algo malo, muy malo)
-- OJO! Para que tenga sentido esta clave SÓLO PUEDE COMUNICARSE POR EMAIL
SELECT S.ID, S.EMAIL, S.EMAIL_V, V.CLAVE FROM `SIMPATIZANTES` S LEFT OUTER JOIN VERIFICACIONES V ON V.ID_SIMP = S.ID WHERE EMAIL_V != 'S';
