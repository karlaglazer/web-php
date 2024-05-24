<h2>{$naziv}</h2>
<section class="popis">
    <table>
        <thead>
            <tr>
                <th>
                    Ime i prezime
                </th>
                <th>
                    Korisnicko ime
                </th>
                <th>
                    Tip računa
                </th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$korisnici item=korisnik}
                <tr>
                    <td>
                        {$korisnik['ime']} {$korisnik['prezime']}
                    </td>
                    <td>
                        {$korisnik['korisnicko_ime']}
                    </td>
                    <td>
                        {$korisnik['blokiran']}
                    </td>
                    <td>
                        {if $korisnik['blokiran']==1}
                            <a href='?aktivacija=1&id={$korisnik['id_korisnik']}'>Odblokiraj</a>
                        {else}
                            <a href='?blokiranje=1&id={$korisnik['id_korisnik']}'>Blokiraj</a>
                        {/if}
                    </td>
                </tr>
            {/foreach}
        </tbody>
    </table>
</section>
