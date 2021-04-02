<form>
    <div class="topBarButtons">
        <button class="button" type="button"><span class="icon-cancel"></span><?= t("Core.Panel.Common.Cancel") ?>
        </button>
        <button class="button"><span class="icon-save"></span><?= t("Core.Panel.Common.Save") ?></button>
    </div>
    <div class="grid page-Page page-Page-edit">
        <input name="id" type="hidden">
        <section class="card" data-width="6">
            <header>
                <h1><?= t("Core.Panel.Page.Page") ?></h1>
            </header>
            <label>
                <span><?= t("Core.Panel.Page.Fields.path") ?></span>
                <input name="path">
            </label>
            <label>
                <span><?= t("Core.Panel.Page.Fields.parent") ?></span>
                <input name="parent_id">
            </label>

            <label>
                <span><?= t("Core.Panel.Page.Fields.title") ?></span>
                <input name="title">
            </label>
            <label>
                <span><?= t("Core.Panel.Page.Fields.description") ?></span>
                <textarea name="description"></textarea>
            </label>
        </section>
        <?php if ($data['type'] == 'edit') { ?>
            <section class="card" data-width="4">
                <div class="parameters"></div>
            </section><?php } else { ?>
            <section class="card" data-width="4">
                <?php foreach ($data['availableComponents'] as $component) {
                    ?>
                    <label>
                        <input type="radio" name="component" value="<?=htmlspecialchars(json_encode($component))?>"><?= (empty($component[0]) ? '' : ($component[0].'/')).$component[1] ?>
                    </label>
                    <?php
                } ?>
            </section>
        <?php } ?>
        <section class="card pageSimulator" data-width="2">
            <h1><?= t("Core.Panel.Page.preview") ?></h1>
            <button type="button" class="pageSimulator-changeResolution" data-width="320" data-height="480" data-top-margin="0">Mobile</button>
            <button type="button" class="pageSimulator-changeResolution" data-width="786" data-height="1024" data-top-margin="100">Tablet</button>
            <button type="button" class="pageSimulator-changeResolution" data-width="1366" data-height="786" data-top-margin="100">Laptop</button>
            <button type="button" class="pageSimulator-changeResolution" data-width="1920" data-height="1080" data-top-margin="100">PC</button>
            <button type="button" class="pageSimulator-changeResolution" data-width="3860" data-height="2160" data-top-margin="100">PC 4k</button>
            <div class="pageSimulator-iframeWrapper">
                <iframe src="/PageSimulator" class="pageSimulator-iframe" name="pageSimulator"></iframe>
            </div>
        </section>
    </div>
</form>
