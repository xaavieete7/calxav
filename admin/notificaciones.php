<?php
$conn = mysqli_connect("db5001646814.hosting-data.io", "dbu1060335", "Ionos123!", "dbs1366328");

$args = "SELECT * FROM `notificaciones`";
$sql = mysqli_query($conn, $args);

$html = '<table class="table">
<thead>
    <tr class="ranking-first">
        <th scope="col"> Títol </th>
        <th scope="col"> Contingut </th>
        <th scope="col"> Visible </th>
        <th scope="col"> Tipus </th>
        <th scope="col" class="text-center"> Opcions </th>
    </tr>
</thead>';
while ($rows=mysqli_fetch_assoc($sql)) {

    $id = $rows['id'];
    $title = $rows['title'];
    $content = $rows['content'];
    $visibility = $rows['visibility'];
    $type = $rows['type'];

    $html .= '<tr>
	    <td scope="row">'. $title . '</td>
	    <td scope="row">'. $content . '</td>
        <td scope="row">'. $visibility . '</td>
        <td scope="row">'. $type . '</td>
        <td class="option_td"><a href="javascript:removeEvent('. $id . ');" class="remove_event"><i class="material-icons">delete</i></a></td>
	</tr>';
}
$html .= '</table>';

print($html);

?>