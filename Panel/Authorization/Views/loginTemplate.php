<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= htmlspecialchars($this->getTitle()) ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/Dist/panelStyle.css" rel="stylesheet" type="text/css">
    <link rel="manifest" href="/dist/Common/manifest.json">
    <link rel="shortcut icon" href="/dist/Common/icon.png">
    <link rel="icon" sizes="192x192" href="/dist/Common/icon192.png">
    <meta name="theme-color" content="#d7ee1b">
    <base href="/panel/">
</head>
<body>
<?php $this->showViews('main'); ?>
<script>
    //<![CDATA[
    window.controllerInitInfo = <?=json_encode($this->getInitInfo())?>;
    window.DEBUG =<?=json_encode($this->isDebug())?>;
    //]]>
</script>
<script src="/dist/panelJs.js" type="text/javascript"></script>
</body>
</html>