<div class="admin-box">
    <table class="table table-striped table-bordered">
        <tbody>
            <?php foreach ($info as $key => $val) : ?>
            <tr>
                <th><?php e(lang($key)); ?></th>
                <td><?php e($val); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>