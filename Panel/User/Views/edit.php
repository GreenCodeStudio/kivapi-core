<form class="dataForm">
    <div class="topBarButtons">
        <button class="button" type="button"><span class="icon-cancel"></span>Anuluj</button>
        <button class="button"><span class="icon-save"></span>Zapisz</button>
    </div>
    <input name="id" type="hidden">
    <section class="card" data-width="6">
        <header>
            <h1><?= t("Core.Panel.User.BasicData") ?></h1>
        </header>
        <label>
            <span><?= t("Core.Panel.User.Fields.name") ?></span>
            <input name="name">
        </label>
        <label>
            <span><?= t("Core.Panel.User.Fields.surname") ?></span>
            <input name="surname">
        </label>
        <label>
            <span><?= t("Core.Panel.User.Fields.mail") ?></span>
            <input name="mail">
        </label>
    </section>
    <section class="card" data-width="6">
        <header>
            <h1><?= t("Core.Panel.User.PasswordChange") ?></h1>
        </header>
        <label>
            <span><?= t("Core.Panel.User.Fields.password") ?></span>
            <input name="password" type="password" autocomplete="new-password">
        </label>
        <label>
            <span><?= t("Core.Panel.User.Fields.repeatPassword") ?></span>
            <input name="password2" type="password" autocomplete="new-password">
        </label>
    </section>

    <section class="card" data-width="6">
        <header>
            <h1><?= t("Core.Panel.User.Permissions") ?></h1>
        </header>
        <?php
        foreach ($data['permissionsStructure'] as $permGroup) {
            ?>
            <h2><?= htmlspecialchars($permGroup->title) ?></h2>
            <?php
            foreach ($permGroup->children as $perm) {
                ?>
                <label>
                    <span><?= htmlspecialchars($perm->title) ?></span>
                    <input type="checkbox"
                           name="permission[<?= htmlspecialchars($permGroup->name) ?>][<?= htmlspecialchars($perm->name) ?>]">
                </label>
                <?php
            }
        }
        ?>
    </section>
</form>