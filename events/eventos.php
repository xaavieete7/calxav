<?php

$args = "SELECT * FROM `eventos_importantes`";

$conn = mysqli_connect("db5001646814.hosting-data.io", "dbu1060335", "Ionos123!", "dbs1366328");
$sql = mysqli_query($conn, $args);

$html = '<table class="table">
<thead>
    <tr class="ranking-first">
        <th scope="col"> Títol </th>
        <th scope="col"> Data </th>
        <th scope="col"> Color </th>
        <th scope="col" class="text-center"> Opcions </th>
    </tr>
</thead>';
while ($rows=mysqli_fetch_assoc($sql)) {
    $id = $rows['id'];
    $title = $rows['title'];
    $date = $rows['start'];
    $color = $rows['color'];

    $html .= '<tr>
	    <td scope="row">'. $title . '</td>
	    <td scope="row">'. $date . '</td>
        <td><input type="color" disabled="disabled" value="'. $color . '"></td>
        <td class="option_td"><a href="javascript:removeEvent('. $id . ');" class="remove_event"><i class="material-icons">delete</i></a></td>
	</tr>';
}
$html .= '</table>';

print($html);

?>