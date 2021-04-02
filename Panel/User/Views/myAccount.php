<section class="card" data-width="6">
    <header>
        <h1><?=htmlspecialchars($data['user']->name)?> <?=htmlspecialchars($data['user']->surname)?></h1>
    </header>
    <div>
        <span>Imię:</span>
        <strong><?= htmlspecialchars($data['user']->name) ?></strong>
    </div>
    <div>
        <span>Nazwisko:</span>
        <strong><?= htmlspecialchars($data['user']->surname) ?></strong>
    </div>
    <div>
        <span>Mail:</span>
        <strong><?= htmlspecialchars($data['user']->mail) ?></strong>
    </div>
</section>
<form class="changePassword">
    <section class="card" data-width="6">
        <header>
            <h1>Zmiana hasła</h1>
        </header>
        <label>
            <span>Hasło</span>
            <input name="password" type="password" autocomplete="new-password">
        </label>
        <label>
            <span>Powtórz</span>
            <input name="password2" type="password" autocomplete="new-password">
        </label>
        <footer>
            <button>Zmień</button>
        </footer>
    </section>
</form>