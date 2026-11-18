<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= $this->renderSection('title') ?> | School System</title>
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>

<body>
    <?= $this->include('Partials/admin_navbar') ?>

    <main class="container">
        <?= $this->renderSection('content') ?>
    </main>

    <?= $this->include('Partials/admin_footer') ?>
</body>

</html>