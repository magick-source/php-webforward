<?php
set_include_path(get_include_path()
	.':'.$_CONF['peardb_path']
	.':'.$_CONF['smarty_path']
);

include_once('model/user.php');
include_once('model/domain.php');
?>
