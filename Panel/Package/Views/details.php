
<div class="topBarButtons">
    <button class="installBtn">Install</button>
</div>
<section class="card">
    <h1>
        <span><?= htmlspecialchars($data['item']->vendor) ?></span>
        <strong><?= htmlspecialchars($data['item']->name) ?></strong>
    </h1>
    <?php if (!empty($data['item']->git)) { ?>
        <div>
            <strong>Git</strong>
            <span>
                <a href="<?= htmlspecialchars($data['item']->git) ?>"><?= htmlspecialchars($data['item']->git) ?></a>
            </span>
        </div>
    <?php } ?>
    <?php if (!empty($data['item']->version)) { ?>
        <div>
            <strong>Version</strong>
            <span>
                <?= htmlspecialchars($data['item']->version) ?>
            </span>
        </div>
    <?php } ?>
</section>
<?php if (!empty($data['item']->description)) { ?>
    <section class="card">
        <h1>Description</h1>
        <?= htmlspecialchars($data['item']->description) ?>
    </section>
<?php } ?>
