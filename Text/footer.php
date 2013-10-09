<p>
	<b>Basisvaardigheden 2012</b> | 
	<?php
		echo "<a href='";
		include('schoolinfo/url.txt');
		echo "' title='";
		include('schoolinfo/naam.txt');
		echo "'>";
		include('schoolinfo/naam.txt');
		echo "</a> | <a href='";
		include('schoolinfo/locatieurl.txt');
		echo "' title='";
		include('schoolinfo/locatienaam.txt');
		echo "'>";
		include('schoolinfo/locatienaam.txt');
		echo "</a> | ";
	?>
	<b>&copy; Leon Kielstra en Jeroen Smienk</b> | 
	<b><a href="#" onClick="alert2()">Info</a></b><br />
	<?php
		if (isset($_SESSION['queries'])){
			echo $_SESSION['queries'] . " queries succeeded";
			unset($_SESSION['queries']);
		}
	?>	
</p>

<script type="text/javascript">
<!--//
function alert2(){
	var i = "©";
	alert ("Basisvaardigheden 2012 ©\nOntwikkeld door Leon Kielstra en Jeroen Smienk.");
}
//-->
</script>