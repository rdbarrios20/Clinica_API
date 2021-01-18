SELECT * FROM clinica.servicios;
INSERT INTO `clinica`.`servicios`
(`id_servicio`,
`nombre`,
`created_at`,
`updated_at`)
VALUES
(1,
'uci',
now(),
now());

INSERT INTO `clinica`.`servicios`
(`id_servicio`,
`nombre`,
`created_at`,
`updated_at`)
VALUES
(2,
'urgencias',
now(),
now());

use clinica;
select ingresos.id_paciente_admision,ingresos.id_servicio,servicios.nombre as nombre_servicio,ingresos.created_at
from ingresos inner join servicios
on ingresos.id_servicio = servicios.id_servicio;