<?php
	function isBlank($value)
	{
		if (isset($value))
		{
			return ( trim($value," ") == "" );
		}
		else
		{
			return false;
		}
	}
?>