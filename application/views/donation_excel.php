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
                <center>Donasi Ke</center>
            </th>
            <th>
                <center>Kategori</center>
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
        foreach($donation AS $row) { 
        $name   = $row->cus_name;
        if($row->anonymous == 'yes'){
            $name   = 'Hamba Allah';
        }

        $category   = ucfirst($row->category);
        if($row->category == 'donation') {
            $category   = 'Donasi';
        }
        ?>
        <tr>
            <td>
                <center><?php echo $no++; ?></center>
            </td>
            <td>
                <center><?php echo $name; ?></center>
            </td>
            <td>
                <center><?php echo $row->donation_name; ?></center>
            </td>
            <td>
                <center><?php echo $category; ?></center>
            </td>
            <td>
                <center><?php echo $row->credit; ?></center>
            </td>
            <td>
                <center><?php echo date('Y-m-d H:i', strtotime($row->created_on)); ?></center>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>