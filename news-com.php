<?php

require_once "config/conn-mysqli.php";

$sql = "SELECT * FROM news ORDER BY id DESC LIMIT 5 ";
$result = $connect->query($sql);

?>
<div class="mt-3">
    <table class="table text-center">
        <thead>
            <tr>
                <th scope="col">หัวเรื่อง</th>
                <th scope="col">วันที่</th>
                <th scope="col">อ่าน</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <tr>
                    <th scope="row"><?php echo $row['title']; ?></th>
                    <td><?php echo $row['time']; ?></td>
                    <td><a class="btn btn-primary btn-sm" href="./news-details.php?id=<?php echo $row['id'] ?>" role="button">อ่าน</a></td>
                </tr>
            <?php endwhile ?>
        </tbody>
    </table>
</div>

<!--  -->