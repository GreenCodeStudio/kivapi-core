<form class="dataForm">
    <div class="topBarButtons">
        <button class="button" type="button"><span class="icon-cancel"></span>Anuluj</button>
        <button class="button"><span class="icon-save"></span>Zapisz</button>
    </div>
    <input name="id" type="hidden">
    <section class="card" data-width="6">
        <header>
            <h1>Kod śledzenia</h1>
        </header>
        <label>
            <span>Nazwa</span>
            <input name="name" type="text">
        </label>
        <label>
            <span>Czy aktywny?</span>
            <input name="is_active" type="checkbox">
        </label>
        <p>Możesz tu dodać kod HTML takich anrzędzi, jak np. Google Analitycs</p>
        <label>
            <span>Kod HTML w sekcji header</span>
            <textarea name="header"></textarea>
        </label>
        <label>
            <span>Kod HTML na początku sekcji body</span>
            <textarea name="body_start"></textarea>
        </label>
        <label>
            <span>Kod HTML na końcu sekcji body</span>
            <textarea name="body_end"></textarea>
        </label>
    </section>
</form>