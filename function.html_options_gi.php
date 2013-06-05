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
 * @version 1.0.1
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

    $extra = '';

    foreach ($params as $_key => $_val) {
        switch ($_key) {
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
			$_html_result .= smarty_function_html_options_gi_optoutput($row[$values], $row[$output], $selected, $id, $class, $_idx);
		}
	}

    if (!empty($name)) {
        $_html_class = !empty($class) ? ' class="'.$class.'"' : '';
        $_html_id = !empty($id) ? ' id="'.$id.'"' : '';
        $_html_result = '<select name="' . $name . '"' . $_html_class . $_html_id . $extra . '>' . "\n" . $_html_result . '</select>' . "\n";
    }

    return $_html_result;
}

function smarty_function_html_options_gi_optoutput($key, $value, $selected, $id, $class, &$idx)
{
    if (!is_array($value)) {
        $_key = smarty_function_escape_special_chars($key);
        $_html_result = '<option value="' . $_key . '"';
        if (is_array($selected)) {
            if (isset($selected[$_key])) {
                $_html_result .= ' selected="selected"';
            }
        } elseif ($_key === $selected) {
            $_html_result .= ' selected="selected"';
        }
        $_html_class = !empty($class) ? ' class="'.$class.' option"' : '';
        $_html_id = !empty($id) ? ' id="'.$id.'-'.$idx.'"' : '';
        if (is_object($value)) {
            if (method_exists($value, "__toString")) {
                $value = smarty_function_escape_special_chars((string) $value->__toString());
            } else {
                trigger_error("html_options_gi: value is an object of class '". get_class($value) ."' without __toString() method", E_USER_NOTICE);
                return '';
            }
        }
        $_html_result .= $_html_class . $_html_id . '>' . $value . '</option>' . "\n";
        $idx++;
    } else {
        $_idx = 0;
        $_html_result = smarty_function_html_options_gi_optgroup($key, $value, $selected, !empty($id) ? ($id.'-'.$idx) : null, $class, $_idx);
        $idx++;
    }
    return $_html_result;
}

function smarty_function_html_options_gi_optgroup($key, $values, $selected, $id, $class, &$idx)
{
    $optgroup_html = '<optgroup label="' . smarty_function_escape_special_chars($key) . '">' . "\n";
    foreach ($values as $key => $value) {
        $optgroup_html .= smarty_function_html_options_gi_optoutput($key, $value, $selected, $id, $class, $idx);
    }
    $optgroup_html .= "</optgroup>\n";
    return $optgroup_html;
}

?>
