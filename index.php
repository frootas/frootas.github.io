<?php
	include 'header.php';
?>

	<div class="body-class">
		<h1>Login system</h1>
		<p>This is a login system</p>
		<p id='textID'></p>
		
		<?php
			// echo "<p id='textID'></p>";
			$var = "this is a variable";
			echo "
			<script type='text/javascript'>
				textID.innerHTML = 'must define p id=textID before the script $var<br>';

			</script>
			";
			
		?>

	</div>
<?php
	include 'footer.php';
?>
