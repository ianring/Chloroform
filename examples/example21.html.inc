<form id="example21">
	<fieldset>
		<div>
			<label>Show validation message in: </label>
			<input class="langchange" type="radio" name="language" value="en" checked="checked" /> English
			<input class="langchange" type="radio" name="language" value="fr" /> French
		</div>
		<hr/>
		<div>
			<label>Enter a prime number</label>
			<input type="text" name="len" data-validate="isprime" value="" />
		</div>
		<button type="submit" class="btn btn-primary">Submit</button>
	</fieldset>
</form>	
