2014-01-30 Roberto.

La funcionalidad de Roaming está incluida en el componente 'irbtools'.
Para instalar el cron primero debemos instalar el componente de forma standard en Joomla. El script que deberá ejecutar
el cron es 'cron_send_delayed_roamings.php'.
La particularidad es que debe de ejecutarse desde la raiz del website. Para ello, despues de instalar el componente
nos posicionamos en el directorio root del website y ejecutamos:

$ cp ./components/com_irbtools/helpers/cron_send_delayed_roamings.php .

Ahora podemos configurar el cron, que será de la forma

0 23 * * * wget http://intranet.irb.pcb.ub.es/cron_send_delayed_roamings.php

El log la ejecucion de este script se encuentra en el directorio root del website, en el folder:

<root>/logs/log_roamings-<month-year>.log