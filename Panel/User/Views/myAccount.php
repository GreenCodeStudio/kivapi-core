<section class="card" data-width="6">
    <header>
        <h1><?= htmlspecialchars($data['user']->name) ?> <?= htmlspecialchars($data['user']->surname) ?></h1>
    </header>
    <div>
        <span><?= t("Core.Panel.User.Fields.name") ?>:</span>
        <strong><?= htmlspecialchars($data['user']->name) ?></strong>
    </div>
    <div>
        <span><?= t("Core.Panel.User.Fields.surname") ?>:</span>
        <strong><?= htmlspecialchars($data['user']->surname) ?></strong>
    </div>
    <div>
        <span><?= t("Core.Panel.User.Fields.mail") ?>:</span>
        <strong><?= htmlspecialchars($data['user']->mail) ?></strong>
    </div>
</section>
<form class="changePassword">
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
        <footer>
            <button><?= t("Core.Panel.User.PasswordChange.Change") ?></button>
        </footer>
    </section>
</form>
