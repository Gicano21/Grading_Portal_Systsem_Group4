<?= $this->extend('Layout/portal') ?>
<?= $this->section('title') ?>Edit <?= esc(rtrim($config['title'], 's')) ?><?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="page-heading">
    <div><p class="eyebrow">SYSTEM ADMINISTRATION</p><h1>Edit <?= esc(rtrim($config['title'], 's')) ?></h1></div>
    <a class="button button-secondary" href="<?= site_url('admin/records/' . $entity) ?>">Back to <?= esc($config['title']) ?></a>
</div>
<section class="panel login-panel">
    <form method="post" action="<?= site_url('admin/records/' . $entity . '/' . $record[$config['primaryKey']]) ?>" class="form-stack record-form">
        <?= csrf_field() ?>
        <?= $this->include('admin/_record_fields') ?>
        <button class="button button-primary" type="submit">Save changes</button>
    </form>
</section>
<?= $this->endSection() ?>
