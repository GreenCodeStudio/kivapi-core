<!DOCTYPE html>
<html <?= isset($meta->lang) ? 'lang="'.htmlspecialchars($meta->lang).'"' : '' ?>>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    include __DIR__.'/../BuildResults/Dist/style.html';
    ?>
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
<?php
include __DIR__.'/../BuildResults/Dist/js.html';
?>
<?php
foreach ($trackingCodes as $trackingCode) {
    echo $trackingCode->body_end;
}
if (!empty($panelData)) {
    ?>
    <style>
        .adminMenu {
            position: fixed;
            bottom: 0;
            left: 0;
            border: 1px solid white;
            background: black;
            color: white;
            padding: 3px;
        }

        .adminMenu-content {
            display: none;
        }

        .adminMenu:hover .adminMenu-content {
            display: block;
        }

        .adminMenu:hover .adminMenu-content a {
            display: block;
            color: white;
            border: 1px solid #888;
            background: #222;
            margin: 5px 0;
        }
    </style>
    <div class="adminMenu">
        <div class="adminMenu-icon">Kivapi admin menu</div>
        <div class="adminMenu-content">
            <a href="<?= $panelData->panelURL ?>">Panel</a>
            <a href="<?= $panelData->editURL ?>">Edit</a>
            <a href="<?= $panelData->inSiteEditURL ?>">InSite Edit</a>
        </div>
    </div>
    <script>
        //<![CDATA[
        window.inSiteEditData = <?=json_encode($panelData->inSiteEditData)?>;
        //]]>
    </script>
    <?php

    include __DIR__.'/../BuildResults/Dist/inSiteEditJs.html';
}
?>
</body>

</html>
