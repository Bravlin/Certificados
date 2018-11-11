<?php

function conectadb()
{
	$link= mysqli_connect ("127.0.0.1","cibercrimen","password","cibercrimen"); 
	if (!mysqli_error($link))
	{
		mysqli_set_charset($link, "utf8");
		return $link;
	}
	else
		return 0;
}

?>
