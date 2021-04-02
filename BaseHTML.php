<!DOCTYPE html>
<html>
<head>
    <link href="/Dist/style.css" rel="stylesheet">
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
    ?>
</head>

<body>
<?php
$component->loadView();
?>
<script src="/Dist/js.js"></script>
</body>

</html>