	<div class="hero-unit">
		<h1>An Error Has Occured</h1>
		<p>
			<strong>Code:</strong> <?php echo $exception->getCode(); ?>
		</p>
		<p>
			<strong>Message:</strong> <?php echo $exception->getMessage(); ?>
		</p>
		<p>
			<strong>Exception:</strong>
			<pre class="pre-scrollable"><?php echo nl2br($exception); ?></pre>
		</p>
	</div>