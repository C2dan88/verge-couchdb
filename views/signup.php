	<h2>Signup Now!</h2>
	
	<form action="<?php echo $this->make_route('/signup'); ?>" method="post">
		<label for="name">Name</label>
		<input id="name" name="name" type="text" />
		<input type="submit" value="Submit" />
	</form>