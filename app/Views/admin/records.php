<?= $this->extend('Layout/portal') ?>
<?= $this->section('title') ?>Manage <?= esc($config['title']) ?><?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="page-heading">
    <div><p class="eyebrow">SYSTEM ADMINISTRATION</p><h1>Manage <?= esc($config['title']) ?></h1></div>
    <a class="button button-secondary" href="<?= site_url('admin/dashboard') ?>">Back to overview</a>
</div>
<section class="panel">
    <h2>Add <?= esc(rtrim($config['title'], 's')) ?></h2>
    <form method="post" action="<?= site_url('admin/records/' . $entity) ?>" class="form-stack record-form">
        <?= csrf_field() ?>
        <?php $record = []; ?>
        <?= $this->include('admin/_record_fields') ?>
        <button class="button button-primary" type="submit">Add record</button>
    </form>
</section>
<section class="panel">
    <h2><?= esc($config['title']) ?></h2>
    <?php if ($records === []): ?>
        <p class="empty-state">No records found.</p>
    <?php else: ?>
        <div class="table-wrap"><table>
            <thead><tr><?php foreach ($config['fields'] as $field): ?><th><?= esc($field['label']) ?></th><?php endforeach; ?><th>Actions</th></tr></thead>
            <tbody><?php foreach ($records as $record): ?>
                <tr>
                    <?php foreach ($config['fields'] as $name => $field): ?><td><?= esc((string) $record[$name]) ?></td><?php endforeach; ?>
                    <td class="record-actions">
                        <a class="button button-small button-secondary" href="<?= site_url('admin/records/' . $entity . '/' . $record[$config['primaryKey']] . '/edit') ?>">Edit</a>
                        <form method="post" action="<?= site_url('admin/records/' . $entity . '/' . $record[$config['primaryKey']] . '/delete') ?>" onsubmit="return confirm('Delete this record? Records in use cannot be deleted.');">
                            <?= csrf_field() ?>
                            <button class="button button-small button-quiet" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?></tbody>
        </table></div>
    <?php endif; ?>
</section>
<?= $this->endSection() ?>
