<?php
/**
 * Smarty plugin
 *
 * @package Smarty
 * @subpackage PluginsFunction
 */

/**
 * Smarty {html_options_gi} function plugin
 *
 * Type:     function<br>
 * Name:     html_options_gi<br>
 * Purpose:  Prints the list of <option> tags generated from
 *           the passed parameters<br>
 * Params:
 * <pre>
 * - name       (optional) - string default "select"
 * - values     (required) - string -> The key of the options array for the value  in OPTION element
 * - output     (required) - string -> The key of the options array for the output in OPTION element
 * - options    (required) - associative array
 * - selected   (optional) - string default not set
 * - id         (optional) - string default not set. Only if name is not empty
 * - class      (optional) - string default not set. Only if name is not empty
 * </pre>
 *
 * @link http://www.smarty.net/manual/en/language.function.html.options.php {html_image}
 *      (Smarty online manual)
 * @author Matias Perrone (mayor changes) <matias dot perrone at globalinnovation dot com dot ar>
 * @param array                    $params   parameters
 * @param Smarty_Internal_Template $template template object
 * @return string
 * @uses smarty_function_escape_special_chars()
 * @version 1.1.0
 * @since 2013-07-01
 */
function smarty_function_html_options_gi($params, $template)
{
    require_once(SMARTY_PLUGINS_DIR . 'shared.escape_special_chars.php');

    $name = null;
    $values = null;
    $options = null;
    $selected = null;
    $output = null;
    $id = null;
    $class = null;
    $data = null;

    $extra = '';

    foreach ($params as $_key => $_val)
    {
        switch ($_key)
        {
            case 'name':
            case 'class':
            case 'id':
            case 'values':
            case 'output':
                $$_key = (string) $_val;
                break;

            case 'options':
                $options = (array) $_val;
                break;

            case 'data':
                $data = json_decode($_val, true);
                if (!$data)
                	trigger_error("html_options_gi: data attribute contains incorrect JSON encoding", E_USER_NOTICE);
                break;

            case 'selected':
                if (is_array($_val)) {
                    $selected = array();
                    foreach ($_val as $_sel) {
                        if (is_object($_sel)) {
                            if (method_exists($_sel, "__toString")) {
                                $_sel = smarty_function_escape_special_chars((string) $_sel->__toString());
                            } else {
                                trigger_error("html_options_gi: selected attribute contains an object of class '". get_class($_sel) ."' without __toString() method", E_USER_NOTICE);
                                continue;
                            }
                        } else {
                            $_sel = smarty_function_escape_special_chars((string) $_sel);
                        }
                        $selected[$_sel] = true;
                    }
                } elseif (is_object($_val)) {
                    if (method_exists($_val, "__toString")) {
                        $selected = smarty_function_escape_special_chars((string) $_val->__toString());
                    } else {
                        trigger_error("html_options_gi: selected attribute is an object of class '". get_class($_val) ."' without __toString() method", E_USER_NOTICE);
                    }
                } else {
                    $selected = smarty_function_escape_special_chars((string) $_val);
                }
                break;

            default:
                if (!is_array($_val)) {
                    $extra .= ' ' . $_key . '="' . smarty_function_escape_special_chars($_val) . '"';
                } else {
                    trigger_error("html_options_gi: extra attribute '$_key' cannot be an array", E_USER_NOTICE);
                }
                break;
        }
    }

    if (!isset($options) Or !isset($values) Or !isset($output)) {
        trigger_error("html_options_gi: attributes options, values and output are not optional", E_USER_NOTICE);
        return '';
    }

    $_html_result = '';
    $_idx = 0;

	foreach ($options as $row)
	{
		if (is_array($row) And count($row))
		{
			$_html_result .= smarty_function_html_options_gi_optoutput($row, $values, $output, $selected, $data);
		}
	}

    if (!empty($name))
    {
        $_html_class = !empty($class) ? ' class="'.$class.'"' : '';
        $_html_id = !empty($id) ? ' id="'.$id.'"' : '';
        $_html_result = '<select name="' . $name . '"' . $_html_class . $_html_id . $extra . '>' . "\n" . $_html_result . '</select>' . "\n";
    }

    return $_html_result;
}

function smarty_function_html_options_gi_optoutput($row, $value, $output, $selected, &$data)
{
    if (!is_array($value))
    {
        $_html_result = '<option value="' . smarty_function_escape_special_chars($row[$value]) . '"';

        if (is_array($selected))
        {
            if (array_key_exists($row[$value], $selected))
            {
                $_html_result .= ' selected="selected"';
            }
        }
        elseif ($row[$value] === $selected)
        {
            $_html_result .= ' selected="selected"';
        }

        if (is_array($data))
        {
        	foreach($data as $name => $field)
        	{
        		if (array_key_exists($field, $row))
        		{
           			$_html_result .= ' data-'.$name.'="'.smarty_function_escape_special_chars((string) $row[$field]).'"';
        		}
        	}
        }

        if (is_object($row[$output]))
        {
            if (method_exists($row[$output], "__toString"))
            {
                $output = (string) $row[$output]->__toString();
            }
            else
            {
                trigger_error("html_options_gi: value is an object of class '". get_class($row[$output]) ."' without __toString() method", E_USER_NOTICE);
                return '';
            }
        }
        else
        	$output = (string) $row[$output];

        $output = smarty_function_escape_special_chars((string) $output);
        $_html_result .= '>' . $output . '</option>' . "\n";
    }
    else
    {
        $_html_result = smarty_function_html_options_gi_optgroup($row, $value, $output, $selected, $data);
    }
    return $_html_result;
}

function smarty_function_html_options_gi_optgroup(&$row, $value, $output, $selected, &$data)
{
    $optgroup_html = '<optgroup label="' . smarty_function_escape_special_chars($row[$value]) . '">' . "\n";
    foreach ($row[$output] as $key => $value)
    {
        $optgroup_html .= smarty_function_html_options_gi_optoutput($row, $value, $output, $selected, $data);
    }
    $optgroup_html .= "</optgroup>\n";
    return $optgroup_html;
}

?>
