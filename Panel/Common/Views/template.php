<!DOCTYPE html>
<html lang="<?=t('Core.Panel.Common.Lang')?>">
<head>
    <title><?= htmlspecialchars($this->getTitle()) ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/Dist/panelStyle.css" rel="stylesheet" type="text/css">
    <link rel="manifest" href="/Dist/Common/manifest.json">
    <link rel="shortcut icon" href="/Dist/Common/icon.png">
    <link rel="icon" sizes="192x192" href="/Dist/Common/icon192.png">
    <meta name="theme-color" content="#d7ee1b">
    <base href="/panel/">
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-3N4J8G8FPP"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-3N4J8G8FPP');
    </script>
    <!-- Hotjar Tracking Code for https://demo1.kivapi.green-code.studio/ -->
    <script>
        (function(h,o,t,j,a,r){
            h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
            h._hjSettings={hjid:2692529,hjsv:6};
            a=o.getElementsByTagName('head')[0];
            r=o.createElement('script');r.async=1;
            r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
            a.appendChild(r);
        })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
    </script>
</head>
<body>
<header>
    <button type="button" class="hamburgerMenu"><span class="icon-menu"></span></button>
    <h1><?=t('Core.Panel.Common.Template.Title')?></h1>
    <div class="loginInfo">
        <a class="headerButton" href="/User/myAccount"><span class="icon-user"></span></a>
        <div class="loginInfo-expandable">
            <span class="icon-user"><?= htmlspecialchars($userData->name.' '.$userData->surname) ?></span>
            <a href="/User/myAccount" class="button"><?=t('Core.Panel.Common.Template.MyAccount')?></a>
            <div class="button logoutMyselfBtn" title="Wyloguj"><span class="icon-logout"></span><?=t('Core.Panel.Common.Template.Logout')?></div>
            </div>
    </div>
</header>
<aside data-views="aside"><?php $this->showViews('aside'); ?></aside>
<div class="mainContent">
    <div class="topBar">
        <div class="breadcrumb"><?php $this->showBreadcrumb(); ?></div>
    </div>
    <div data-views="main"><?php $this->showViews('main'); ?></div>
</div>
<script>
    //<![CDATA[
    window.controllerInitInfo = <?=json_encode($this->getInitInfo())?>;
    window.DEBUG =<?=json_encode($this->isDebug())?>;
    //]]>
</script>
<script src="/Dist/panelJs.js" type="text/javascript"></script>
</body>
</html>
