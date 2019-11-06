<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=$title.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<table border="1" width="100%">
    <thead>
        <tr>
            <th><center>No</center></th>
            <th>
                <center>Nama</center>
            </th>
            <th>
                <center>Nominal</center>
            </th>
            <th>
                <center>Tanggal</center>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $no = 1;
        foreach($infaq AS $row) { ?>
        <tr>
            <td>
                <center><?php echo $no++; ?></center>
            </td>
            <td>
                <center><?php echo $row->name; ?></center>
            </td>
            <td>
                <center><?php echo $row->debit; ?></center>
            </td>
            <td>
                <center><?php echo date('Y-m-d H:i', strtotime($row->created_on)); ?></center>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>