<section>
    <h2>{$nekretnina['opis']}</h2>
    <img alt="slika" src="{$nekretnina['slika']}" width=500px height=auto/><br>
    <p><b>Cijena najma:</b> {$nekretnina['ukupna_cijena']} EUR<br>
        <b>Datum najma:</b> {$nekretnina['datum_najma']}</p><br>
    <a href="?nekretnina={$nekretnina['id_ugovor']}&raskini=1">Raskini</a>
    <a href="#nedostatak">Prijavi nedostatak</a>
    <div id='nedostatak'>
        <form method="post">
            <input value='{$nekretnina['id_ugovor']}' name='nekretnina' hidden>
            <label for='opisNedostatka'>Nedostatak</label>
            <input name='opisNedostatka' required>
            <label for='vaznost'>Važnost</label>
            <select name='vaznost'>
                {foreach from =$tipovi item=$tip}
                    <option value={$tip['id_vaznost_nedostatka']}>{$tip['vaznost']}</option>
                {/foreach}
            </select><br>
            <button type='submit'>Prijavi</button>
        </form>
    </div>
</section>
