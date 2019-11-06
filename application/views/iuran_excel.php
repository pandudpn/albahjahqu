<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=$title.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<table border="1" width="100%">
    <thead>
        <tr>
            <th>
                <center>No</center>
            </th>
            <th>
                <center>NIS</center>
            </th>
            <th>
                <center>Nama</center>
            </th>
            <th>
                <center>Sekolah</center>
            </th>
            <th>
                <center>Kode Cabang</center>
            </th>
            <th>
                <center>Nominal</center>
            </th>
            <th>
                <center>Tanggal Bayar</center>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $no = 1;
        foreach($iuran AS $row) { ?>
        <tr>
            <td>
                <center><?php echo $no++; ?></center>
            </td>
            <td>
                <center><?php echo $row->nis; ?></center>
            </td>
            <td>
                <center><?php echo $row->nama; ?></center>
            </td>
            <td>
                <center><?php echo $row->sekolah; ?></center>
            </td>
            <td>
                <center><?php echo $row->kode_cabang; ?></center>
            </td>
            <td>
                <center><?php echo $row->nominal; ?></center>
            </td>
            <td>
                <center><?php echo date('Y-m-d H:i', strtotime($row->tanggal)); ?></center>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>