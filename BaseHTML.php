<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/Dist/style.css" rel="stylesheet">
    <meta name="generator" content="Kivapi" />
    <link rel="author" href="https://greencodestudio.github.io/kivapi/" />
    <?php
    if (!empty($meta->title)) {
        ?>
        <title><?= htmlspecialchars($meta->title) ?></title>
        <?php
    }
    ?>
    <?php
    if (!empty($meta->description)) {
        ?>
        <meta name="description" content="<?= htmlspecialchars($meta->description) ?>">
        <?php
    }
    ?>   <?php
    if (!empty($meta->canonical)) {
        ?>
        <link rel="canonical" href="<?= htmlspecialchars($meta->canonical) ?>" />
        <?php
    }
    ?>
</head>

<body>
<?php
$component->loadView();
?>
<script src="/Dist/js.js"></script>
</body>

</html>