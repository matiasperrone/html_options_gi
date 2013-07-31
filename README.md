html_options_gi
===============

<b>html_options_gi</b> is a smarty plugin as alternative of html_options.<br/>
Prints the list of &lt;option&gt; tags generated from a numeric / asociative array.


Parameters:
===========

options    (required) - associative array.<br/>
values     (required) - The key of the options array for the value in OPTION element.<br/>
output     (required) - The key of the options array for the text in OPTION element.<br/>
selected   (optional) - string or array (multiple selects), used to mark as "selected" the options.<br/>
name       (optional) - If this parameter is set then creates a SELECT element with this name.<br/>
id         (optional) - string default not set, used only if name is not empty.<br/>
class      (optional) - string default not set, used only if name is not empty.<br/>
?????      (optional) - string parameter added to the SELECT element.
data       (optional) - string of JSON encoding of an object of "name": "field" used to add as "data-name" to the OPTION elements and populating with the value of "field".

Examples
========

Example of "currency" table as:
<table>
	<tr>
		<td>currency_id</td><td>currency_sign</td><td>currency_iso</td><td>currency_name</td>
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
<pre>
// Assuming $smarty and $db already initialized

$result = mysqli_query($db, "SELECT * FROM currency") or die();
$data = array();
while ($row = mysqli_fetch_assoc($result))
	$data[] = $row;
	
$smarty->assingByRef('data', $data);
</pre>

Example 1:
==========
<pre>
&lt;select id=&quot;currency&quot; name=&quot;currency&quot; style=&quot;width: 67%; height: 30px; float: right;&quot; &gt;
	&lt;option value=&quot;&quot;&gt;[Select a Currency]&lt;/option&gt;
	{html_options_gi values="currency_id" output="currency_name" options=$data}
&lt;/select&gt;
</pre>

# Result:
<pre>
&lt;select id=&quot;currency&quot; name=&quot;currency&quot; style=&quot;width: 67%; height: 30px; float: right;&quot;&gt;
	&lt;option value=&quot;&quot;&gt;[Select a Currency]&lt;/option&gt;
	&lt;option value=&quot;1&quot;&gt;Pesos ARS&lt;/option&gt;
	&lt;option value=&quot;2&quot;&gt;US Dolars&lt;/option&gt;
	&lt;option value=&quot;3&quot;&gt;Eurs&lt;/option&gt;
&lt;/select&gt;
</pre>

Example 2:
==========
if "Select a Currency" is not necessary, and adding "style" as a parameter:
<pre>
{html_options_gi name="currency" id="currency" values="currency_id" output="currency_name"  options=$data style="width: 67%; height: 30px; float: right;"}
</pre>

# Result:
<pre>
&lt;select id=&quot;currency&quot; name=&quot;currency&quot; style=&quot;width: 67%; height: 30px; float: right;&quot;&gt;
	&lt;option value=&quot;1&quot;&gt;Pesos ARS&lt;/option&gt;
	&lt;option value=&quot;2&quot;&gt;US Dolars&lt;/option&gt;
	&lt;option value=&quot;3&quot;&gt;Eurs&lt;/option&gt;
&lt;/select&gt;
</pre>

Example 3:
==========
if "Select a Currency" is not necessary, and adding "style" as a parameter and a "data" parameter:
<pre>
{html_options_gi name="currency" id="currency" values="currency_id" output="currency_name"  options=$data style="width: 67%; height: 30px; float: right;" data='{"iso":"currency_iso"}'}
</pre>

# Result:
<pre>
&lt;select id=&quot;currency&quot; name=&quot;currency&quot; style=&quot;width: 67%; height: 30px; float: right;&quot;&gt;
	&lt;option value=&quot;1&quot; data-iso=&quot;ARS&quot;&gt;Pesos ARS&lt;/option&gt;
	&lt;option value=&quot;2&quot; data-iso=&quot;USD&quot;&gt;US Dolars&lt;/option&gt;
	&lt;option value=&quot;3&quot; data-iso=&quot;EUR&quot;&gt;Eurs&lt;/option&gt;
&lt;/select&gt;
</pre>




