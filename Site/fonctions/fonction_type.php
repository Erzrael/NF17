<?php
function isType($vInteger){
	if($vInteger == 1)
		$vInteger = Client;
	elseif($vInteger == 2)
		$vInteger = Editeur;
	else
		$vInteger = Administrateur;
	return $vInteger;
}
?>