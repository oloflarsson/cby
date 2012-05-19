<?php
$backfields = $cbyconf['backfields'];

// The common thead and tfoot html.
$th_row_html = '<tr>';
foreach($backfields as $key)
{
	$th_row_html .= '<th title="'.htmlspecialchars($cbyconf['fieldinfo'][$key]['desc']).'" >'.htmlspecialchars($cbyconf['fieldinfo'][$key]['backname']).'</th>';
}
$th_row_html .= '</tr>';

?>
<div class="wrap">
<div id="icon-users" class="icon32"><br></div><h2>Anmälda till CBY</h2><br>
<table class="widefat cby_persons tablesorter">
<thead><?= $th_row_html ?></thead>
<tfoot><?= $th_row_html ?></tfoot>
<tbody>
<?php

$q = $em->createQuery("select p from Entities\Person p");
$persons = $q->getResult();
foreach ($persons as $person)
{
	$rowhtml = "<tr>\n";
	foreach($backfields as $key)
	{
		$fieldinfo = $cbyconf['fieldinfo'][$key];
		$classes = array('personfield');
		$classes[] = $key;
		if ($fieldinfo['option'])
		{
			$classes[] = 'option';
		}
		if ($fieldinfo['editable'])
		{
			$classes[] = 'editable';
		}
		
		$classes[] = 'type_'.$fieldinfo['type'];
		
		if ($fieldinfo['type'] == 'textarea')
		{
			$classes[] = 'minitext';
		}
		
		$rowhtml .= '<td class="'.implode(' ', $classes).'" id="person.'.$person->getId().'.'.$key.'">';
		$value = $person->{'desc'.$key}();
		if ($fieldinfo['escape'])
		{
			$value = htmlspecialchars($value);
		}
		$rowhtml .= $value;
		$rowhtml .= "</td>\n";
	}
	$rowhtml .= "</tr>\n";
	echo $rowhtml;
}
?>
</tbody>
</table>
</div>