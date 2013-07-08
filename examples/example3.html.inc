<p>These checkboxes must be checked. They are "required". Click the button to add more of them.</p>

<form id="example3-1">
	<div id="fields">
		<input type="checkbox" data-validate="required" />
	</div>
	<input type="submit" value="Submit" />
	<input type="button" id="add_field" value="Add another checkbox"/>
</form>

<p>Here's a neat example. You need to check at least three of these checkboxes. But there are only two of them! Click the button to add some more.</p>

<form id="example3-2">
	<div id="fields2">
		<input type="checkbox" name="choices2" data-validate="choices(3)" />
		<input type="checkbox" name="choices2" data-validate="choices(3)" />
	</div>
	<input type="submit" value="Submit" />
	<input type="button" id="add_field2" value="Add another checkbox"/>
</form>