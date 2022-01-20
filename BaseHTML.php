<!DOCTYPE html>
<html <?= isset($meta->lang) ? 'lang="' . htmlspecialchars($meta->lang) . '"' : '' ?>>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/Dist/style.css" rel="stylesheet">
    <meta name="generator" content="Kivapi"/>
    <link rel="author" href="https://greencodestudio.github.io/kivapi/"/>
    <?php
    if (!empty($meta->title)) {
        ?>
        <title><?= htmlspecialchars($meta->title) ?></title>
        <?php
    }

    if (!empty($meta->description)) {
        ?>
        <meta name="description" content="<?= htmlspecialchars($meta->description) ?>">
        <?php
    }

    if (!empty($meta->canonical)) {
        ?>
        <link rel="canonical" href="<?= htmlspecialchars($meta->canonical) ?>"/>
        <?php
    }
    foreach ($trackingCodes as $trackingCode) {
        echo $trackingCode->header;
    }

    ?>
</head>

<body>
<?php
foreach ($trackingCodes as $trackingCode) {
    echo $trackingCode->body_start;
}
$component->loadView();
?>
<script>
    //<![CDATA[
    window.controllerInitInfo = <?=json_encode($initInfo)?>;
    //]]>
</script>
<script src="/Dist/js.js"></script>
<?php
foreach ($trackingCodes as $trackingCode) {
    echo $trackingCode->body_end;
}
?>
</body>

</html>