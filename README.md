html_options_gi - version 1.1
=============================

<b>html_options_gi</b> is a smarty plugin as alternative of html_options.<br/>
Prints the list of &lt;option&gt; tags generated from a numeric / asociative array.


Parameters:
===========

options    (required) - associative array.
values     (required) - The key of the options array for the value in OPTION element.
output     (required) - The key of the options array for the text in OPTION element.
selected   (optional) - string or array (multiple selects), used to mark as "selected" the options.
name       (optional) - If this parameter is set then creates a SELECT element with this name.
id         (optional) - string default not set, used only if name is not empty.
class      (optional) - string default not set, used only if name is not empty.
?????      (optional) - string parameter added to the SELECT element.
data       (optional) - string of JSON encoding of an object of "name": "field" used to add as "data-name" to the OPTION elements and populating with the value of "field".


Examples
========

Example of "currency" table as:
<table>
	<tr>
		<th>currency_id</th><th>currency_sign</th><th>currency_iso</th><th>currency_name</td>
	</tr>
	<tr>
		<td>1</td><td>$</td><td>ARS</td><td>Pesos ARS</td>
	</tr>
	<tr>
		<td>2</td><td>u$s</td><td>USD</td><td>US Dolars</td>
	</tr>
	<tr>
		<td>3</td><td>€</td><td>EUR</td><td>Eurs</td>
	</tr>
</table>

Using PHP:
```php
// Assuming $smarty and $db already initialized
$result = mysqli_query($db, "SELECT * FROM currency") or die();
$data = array();
while ($row = mysqli_fetch_assoc($result))
	$data[] = $row;
$smarty->assingByRef('data', $data);
```

## Example 1:
```html
<select id="currency" name="currency" style="width: 67%; height: 30px; float: right;">
	<option value="">[Select a Currency]</option>
	{html_options_gi values="currency_id" output="currency_name" options=$data}
</select>
```

### Result:
```html
<select id="currency" name="currency" style="width: 67%; height: 30px; float: right;">
	<option value="">[Select a Currency]</option>
	<option value="1">Pesos ARS</option>
	<option value="2">US Dolars</option>
	<option value="3">Eurs</option>
</select>
```

## Example 2:
if "Select a Currency" is not necessary, and adding "style" as a parameter:
```html
{html_options_gi name="currency" id="currency" values="currency_id" output="currency_name"  options=$data style="width: 67%; height: 30px; float: right;"}
```

### Result:
```html
<select id="currency" name="currency" style="width: 67%; height: 30px; float: right;">
	<option value="1">Pesos ARS</option>
	<option value="2">US Dolars</option>
	<option value="3">Eurs</option>
</select>
```

Example 3:
==========
if "Select a Currency" is not necessary, and adding "style" as a parameter and a "data" parameter:
```html
{html_options_gi name="currency" id="currency" values="currency_id" output="currency_name"  options=$data style="width: 67%; height: 30px; float: right;" data='{"iso":"currency_iso"}'}
```

### Result:
```html
<select id="currency" name="currency" style="width: 67%; height: 30px; float: right;">
	<option value="1" data-iso="ARS">Pesos ARS</option>
	<option value="2" data-iso="USD">US Dolars</option>
	<option value="3" data-iso="EUR">Eurs</option>
</select>
```




