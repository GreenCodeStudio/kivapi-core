<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= htmlspecialchars($this->getTitle()) ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    include __DIR__.'/../../../../BuildResults/Dist/panelStyle.html';
    ?>
    <link rel="manifest" href="/Dist/Common/manifest.json">
    <link rel="shortcut icon" href="/Dist/Common/icon.png">
    <link rel="icon" sizes="192x192" href="/Dist/Common/icon192.png">
    <meta name="theme-color" content="#d7ee1b">
    <base href="/panel/">
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-3N4J8G8FPP"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', 'G-3N4J8G8FPP');
    </script>
    <!-- Hotjar Tracking Code for https://demo1.kivapi.green-code.studio/ -->
    <script>
        (function (h, o, t, j, a, r) {
            h.hj = h.hj || function () {
                (h.hj.q = h.hj.q || []).push(arguments)
            };
            h._hjSettings = {hjid: 2692529, hjsv: 6};
            a = o.getElementsByTagName('head')[0];
            r = o.createElement('script');
            r.async = 1;
            r.src = t + h._hjSettings.hjid + j + h._hjSettings.hjsv;
            a.appendChild(r);
        })(window, document, 'https://static.hotjar.com/c/hotjar-', '.js?sv=');
    </script>
</head>
<body>
<?php $this->showViews('main'); ?>
<script>
    //<![CDATA[
    window.controllerInitInfo = <?=json_encode($this->getInitInfo())?>;
    window.DEBUG = <?=json_encode($this->isDebug())?>;
    //]]>
</script>
<?php
include __DIR__.'/../../../../Public/Dist/panelJs.html';
?>
</body>
</html>
