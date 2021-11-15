<form class="dataForm">
    <div class="topBarButtons">
        <button class="button" type="button"><span class="icon-cancel"></span><?= t("Core.Panel.Common.Cancel") ?></button>
        <button class="button"><span class="icon-save"></span><?= t("Core.Panel.Common.Save") ?></button>
    </div>
    <input name="id" type="hidden">
    <section class="card" data-width="6">
        <header>
            <h1><?= t("Core.Panel.TrackingCode.TrackingCode") ?></h1>
        </header>
        <label>
            <span><?= t("Core.Panel.TrackingCode.Fields.name") ?></span>
            <input name="name" type="text">
        </label>
        <label>
            <span><?= t("Core.Panel.TrackingCode.Fields.is_active") ?></span>
            <input name="is_active" type="checkbox">
        </label>
        <p><?= t("Core.Panel.TrackingCode.Description") ?></p>
        <label>
            <span><?= t("Core.Panel.TrackingCode.Fields.header") ?></span>
            <textarea name="header"></textarea>
        </label>
        <label>
            <span><?= t("Core.Panel.TrackingCode.Fields.body_start") ?></span>
            <textarea name="body_start"></textarea>
        </label>
        <label>
            <span><?= t("Core.Panel.TrackingCode.Fields.body_end") ?></span>
            <textarea name="body_end"></textarea>
        </label>
    </section>
</form>