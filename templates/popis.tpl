<section>
    <h2>Popis nekretnina</h2>
    {if isset($smarty.session.uloga) && $smarty.session.uloga>2}
        <button type="button" id='novaDiv'>Kreiraj novu nekretninu</button><br>
        <button type="button" id='uredivanjeDiv'>Uredi postojeću nekretninu</button>
        <div id='nova' action=''>
            <form method="post" enctype="multipart/form-data">
                <input name='nova' style="display:none">
                <table>
                    <tr>
                        <td><label for='opis'>Opis: </label></td>
                        <td><input id='opis' name='opis' required></td>
                    </tr>
                    <tr>
                        <td><label for='upravitelj'>Upravitelj: </label></td>
                        <td><select name='upravitelj'>
                                {foreach from =$upravitelji item=$upravitelj}
                                    <option value={$upravitelj['id_korisnik']}>{$upravitelj['ime']}</option>
                                {/foreach}
                            </select></td>
                    </tr>
                    <tr>
                        <td><label for='slika'>Slika: </label></td>
                        <td><input type="file" name='slika' required></td>
                    </tr>
                    <tr>
                        <td><label for='cijena'>Cijena najma: </label></td>
                        <td><input name='cijena' required></td>
                    </tr>
                    <tr>
                        <td><label for='tip'>Tip nekretnine: </label></td>
                        <td><select name='tip'>
                                {foreach from =$tip_nekretnine item=$tip}
                                    <option value={$tip['id_tip_nekretnine']}>{$tip['naziv']}</option>
                                {/foreach}
                            </select></td>
                    </tr>
                </table>
                <button type='submit'>Spremi</button>
            </form>
        </div>
        <div id='uredivanje' action=''>
            <form method="post" enctype="multipart/form-data">
                <input name='uredivanje' style="display:none">
                <table>
                    <tr>
                        <td><label for='nekretnine'>Postojeće nekretnine: </label></td>
                        <td><select name='nekretnine'>
                                {foreach from =$nekretnine item=$nekretnina}
                                    <option value={$nekretnina['id_nekretnina']}>{$nekretnina['opis']}</option>
                                {/foreach}
                            </select></td>
                    </tr>
                    <tr>
                        <td><label for='opis'>Opis: </label></td>
                        <td><input id='opis' name='opis' required></td>
                    </tr>
                    <tr>
                        <td><label for='upravitelj'>Upravitelj: </label></td>
                        <td><select name='upravitelj'>
                                {foreach from =$upravitelji item=$upravitelj}
                                    <option value={$upravitelj['id_korisnik']}>{$upravitelj['ime']}</option>
                                {/foreach}
                            </select></td>
                    </tr>
                    <tr>
                        <td><label for='slika'>Slika: </label></td>
                        <td><input type="file" name='slika' required></td>
                    </tr>
                    <tr>
                        <td><label for='cijena'>Cijena najma: </label></td>
                        <td><input name='cijena' required></td>
                    </tr>
                    <tr>
                        <td><label for='tip'>Tip nekretnine: </label></td>
                        <td><select name='tip'>
                                {foreach from =$tip_nekretnine item=$tip}
                                    <option value={$tip['id_tip_nekretnine']}>{$tip['naziv']}</option>
                                {/foreach}
                            </select></td>
                    </tr>
                </table>
                <button type='submit'>Spremi</button>
            </form>
        </div>
    {/if}
    <div class="nekretnine">
        <div id="iznajmljene">
            <h3>Iznajmljene nekretnine</h3>
            <h4>Filteri</h4>
            <form method="post" id="filterIznajmljene" name="filterIznajmljene"> 
                <input name='filterDatum' style="display:none">
                <label for="od">Od: </label>
                <input type="date" name="od" value="{$od}">
                <label for="do">Do: </label>
                <input type="date" name="do" value="{$do}">
                <button type="submit">Filtriraj</button>
            </form>
            <hr>
            <div>
                {foreach from=$iznajmljene item=nekretnina}
                    <div class="nekretnina">
                        <img src="{$nekretnina['slika']}" width=200px height=100px/>
                        <div>
                            Opis: {$nekretnina['opis']}<br>
                            Tip nekretnina: {$nekretnina['naziv']}<br>
                            Upravitelj: {$nekretnina['upravitelj']}<br>
                            Datum najma: {$nekretnina['datum_najma']}
                        </div>
                    </div>
                {/foreach}
            </div>
        </div>
        <div id="slobodne">
            <h3>Slobodne nekretnine</h3>
            <h4>Filteri</h4>
            <form method="post" id="filterSlobodne" name="filterSlobodne"> 
                <input name='filterOpis' style="display:none">
                <label for="opis">Opis: </label>
                <input type="text" name="opis" value="{$opis}">
                <select id="redoslijed" name="redoslijed">
                    <option value="sort0" id="sort0" {if $redoslijed == 'sort0'}selected{/if}>nesortirano</option>
                    <option value="sort1" id="sort1" {if $redoslijed == 'sort1'}selected{/if}>manja-veća</option>
                    <option value="sort2" id="sort2" {if $redoslijed == 'sort2'}selected{/if}>veća-manja</option>
                </select>
                <button type="submit">Filtriraj</button>
            </form>
            <hr>
            <div>
                {foreach from=$slobodne item=nekretnina}
                    <div class="nekretnina">
                        <img src="{$nekretnina['slika']}" width=200px height=100px/>
                        <div>
                            Opis: {$nekretnina['opis']}<br>
                            Tip nekretnina: {$nekretnina['naziv']}<br>
                            Upravitelj: {$nekretnina['upravitelj']}<br>
                            Cijena: {$nekretnina['ukupna_cijena']} EUR
                        </div>
                    </div>
                {/foreach}
            </div>
        </div>
    </div>
</section>