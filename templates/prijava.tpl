<section class="prijava">
    <form novalidate id="prijava" method="post" action="">
        <label for='korime_prijava'>Korisničko ime: </label>
        <input id="korime_prijava" name="korime_prijava" value="{$korisnickoIme}"><br>
        <label for='lozinka_prijava'>Lozinka: </label>
        <input id="lozinka_prijava" name="lozinka_prijava" type="password"><br>
        <input type="checkbox" id="zapamti" name="zapamti"><label>Zapamti</label><br>
        <button type="submit">Prijava</button>
    </form>
        <br>
    {if $greska!=""}
        <div>{$greska}</div>
    {/if}
    <br>
    <a href="#oporavakLozinke">Zaboravljena lozinka</a>
    <div id="oporavakLozinke" action="">
        <form method="get">
            <label for="oporavakEmail">Unesite email: </label>
            <input type="email" id="oporavakEmail" name="oporavakEmail">
            <button type="submit">Pošalji</button>
        </form>
    </div>
</section>
