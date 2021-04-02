<form class="dataForm" data-name="Token" data-controller="Token"
      data-method="<?= $data['type'] == 'edit' ? 'update' : 'insert' ?>" data-goto="/Token">
    <div class="topBarButtons">
        <button class="button" type="button">Anuluj</button>
        <button class="button">Zapisz</button>
    </div>
    <input name="id" type="hidden">
    <section class="card" data-width="6">
        <header>
            <h1>Token</h1>
        </header>
        <label>
            <span>Typ</span>
            <select name="type" required>
                <option value="login">Token do logowania bezhasłowego</option>
            </select>
        </label>
        <label>
            <span>Użytkownik</span>
            <select data-foreign-key="user" name="id_user" required></select>
        </label>
        <label>
            <span>Czas ważności</span>
            <select name="expire">
                <option value="30m">30 minut</option>
                <option value="4h">4 godziny</option>
                <option value="1d">1 dzień</option>
                <option value="forever">bezterminowo</option>
            </select>
        </label>
        <label>
            <span>Token jednorazowy</span>
            <input type="checkbox" name="isOnce">
        </label>
    </section>
</form>