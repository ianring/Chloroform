<form id="example5">
	
	change the minimum length of the text field: 
	<div>
		<label class="radio">
			<input class="minradio" id="min4" type="radio" name="min" value="4" checked="checked" /> 4
		</label>
		<label class="radio">
			<input class="minradio" id="min5" type="radio" name="min" value="5" /> 5
		</label>
		<label class="radio">
			<input class="minradio" id="min6" type="radio" name="min" value="6" /> 6
		</label>
		<label class="radio">
			<input class="minradio" id="min7" type="radio" name="min" value="7" /> 7
		</label>
	</div>

	<div>
		<input id="mytextinput" type="text" value="foo" data-validate="length(4)"/>
	</div>
	
	<input type="submit" value="Submit" />
</form>
