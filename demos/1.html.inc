<form id="demo1">
	<table class="table">
		<tr>
			<th>data-validate attribute</th>
			<th>Description</th>
			<th>Field</th>
		</tr>
		<tr>
			<td>alpha</td>
			<td>letters only</td>
			<td><input type="text" id="field30" data-validate="alpha" value="asdf"/></td>
		</tr>
		<tr>
			<td>numeric</td>
			<td>numbers only, allowing "." and ","</td>
			<td><input type="text" id="field31" data-validate="numeric" value="3.14159"/></td>
		</tr>
		<tr>
			<td>required;alpha</td>
			<td>letters only, but also can not be blank</td>
			<td><input type="text" id="field32" data-validate="required;alpha" value="abc"/></td>
		</tr>
		<tr>
			<td>required;numeric</td>
			<td>numbers only, but also can not be blank</td>
			<td><input type="text" id="field33" data-validate="required;numeric" value="123"/></td>
		</tr>
		<tr>
			<td>integer</td>
			<td></td>
			<td><input type="text" id="field3" data-validate="integer" value="13"/></td>
		</tr>
		<tr>
			<td>!integer</td>
			<td></td>
			<td><input type="text" id="field37" data-validate="!integer" value="3.141"/></td>
		</tr>
		<tr>
			<td>length(5)</td>
			<td>password1</td>
			<td><input type="text" id="password1" data-validate="length(5)" value="qqqqq"/></td>
		</tr>
		<tr>
			<td>sameas(password1)</td>
			<td></td>
			<td><input type="text" id="field5" data-validate="sameas(password1)" value="qqqqq"/></td>
		</tr>
		<tr>
			<td>!sameas(password1)</td>
			<td>NOT the same as password1</td>
			<td><input type="text" id="field5" data-validate="!sameas(password1)" value="qqqqqq"/></td>
		</tr>
		<tr>
			<td>custom1</td>
			<td>must be "123"</td>
			<td><input type="text" id="field6" data-validate="custom1" value="123"/></td>
		</tr>
		<tr>
			<td>required</td>
			<td>must be checked</td>
			<td><input type="checkbox" id="field7" data-validate="required" value="abc" checked="checked"/></td>
		</tr>
		<tr>
			<td>max(10)</td>
			<td></td>
			<td><input type="text" id="field8" data-validate="max(10)" value="6"/></td>
		</tr>
		<tr>
			<td>min(5)</td>
			<td></td>
			<td><input type="text" id="field9" data-validate="min(5)" value="6"/></td>
		</tr>
		<tr>
			<td>between(5,10)</td>
			<td></td>
			<td><input type="text" id="field10" data-validate="between(5,10)" value="6"/></td>
		</tr>
		<tr>
			<td>length(6)</td>
			<td>minimum length 6</td>
			<td><input type="text" id="field11" data-validate="length(6)" value="qqqqqqqq"/></td>
		</tr>
		<tr>
			<td>length(0,16)</td>
			<td>length between 0 and 16</td>
			<td><input type="text" id="field12" data-validate="length(0,16)" value="qqqqqqq"/></td>
		</tr>
		<tr>
			<td>length(6,16)</td>
			<td>length between 6 and 16</td>
			<td><input type="text" id="field13" data-validate="length(6,16)" value="qqqqqqq"/></td>
		</tr>
		<tr>
			<td>length(5,5)</td>
			<td>length exactly 5</td>
			<td><input type="text" id="field40" data-validate="length(5,5)" value="qqqqq"/></td>
		</tr>
		<tr>
			<td>!length(5,5)</td>
			<td>length is NOT exactly 5</td>
			<td><input type="text" id="field40" data-validate="!length(5,5)" value="qqqqqqq"/></td>
		</tr>
		<tr>
			<td>choices(2)</td>
			<td>choose at least two of these</td>
			<td>
				<input type="checkbox" name="choice1" id="field14" data-validate="choices(2)" value="1"/>
				<input type="checkbox" name="choice1" id="field15" data-validate="choices(2)" value="1"/>
				<input type="checkbox" name="choice1" id="field16" data-validate="choices(2)" value="1"/>
				<input type="checkbox" name="choice1" id="field17" data-validate="choices(2)" value="1"/>
			</td>
		</tr>
		<tr>
			<td>choices(0,3)</td>
			<td>choose at most three of these</td>
			<td>
				<input type="checkbox" name="choice2" id="field18" data-validate="choices(0,3)" value="1"/>
				<input type="checkbox" name="choice2" id="field19" data-validate="choices(0,3)" value="1"/>
				<input type="checkbox" name="choice2" id="field20" data-validate="choices(0,3)" value="1"/>
				<input type="checkbox" name="choice2" id="field21" data-validate="choices(0,3)" value="1"/>
			</td>
		</tr>
		<tr>
			<td>choices(2,2)</td>
			<td>choose exactly two of these</td>
			<td>
				<input type="checkbox" name="choice3" id="field22" data-validate="choices(2,2)" value="1"/>
				<input type="checkbox" name="choice3" id="field23" data-validate="choices(2,2)" value="1"/>
				<input type="checkbox" name="choice3" id="field24" data-validate="choices(2,2)" value="1"/>
				<input type="checkbox" name="choice3" id="field25" data-validate="choices(2,2)" value="1"/>
			</td>
		</tr>
		<tr>
			<td>choices(2,2)</td>
			<td>choose exactly two of these</td>
			<td>
				<select data-validate="choices(2,2)" multiple="multiple">
					<option value="1">one</option>
					<option value="2">two</option>
					<option value="3">three</option>
					<option value="4">four</option>
					<option value="5">five</option>
					<option value="6">six</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>contains(zzz)</td>
			<td>must contain zzz</td>
			<td><input type="text" id="field26" data-validate="contains(zzz)" value="aaaazzzaa"/></td>
		</tr>
		<tr>
			<td>!contains(zzz)</td>
			<td>must not contain zzz</td>
			<td><input type="text" id="field27" data-validate="!contains(zzz)" value="aaaaaaa"/></td>
		</tr>
		<tr>
			<td>regex(/^[abcdefg]*$/)</td>
			<td>must contain only the characters a through g (regular expression match)</td>
			<td><input type="text" id="field28" data-validate="regex(^[abcdefg]*$)" value="abcdefabcdefabcdef"/></td>
		</tr>
		<tr>
			<td>regex(/^[abcdefg]*$/)</td>
			<td>must NOT contain the characters a through g (negative regular expression match)</td>
			<td><input type="text" id="field29" data-validate="regex([^abcdefg]*$)" value="xxxxxaxxxx"/></td>
		</tr>
		
		<tr>
			<td></td>
			<td></td>
			<td><input type="submit" class="btn btn-primary"/></td>
		</tr>
		
	</table>
</form>