<h2>{$naziv}</h2>
<section class="popis">
    <table>
        <thead>
            <tr>
                <th>
                    Stanar
                </th>
                <th>
                    Nekretnina
                </th>
                <th>
                    Status
                </th>
                <th>

                </th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$ugovori item=ugovor}
                <tr>
                    <td>
                        {$ugovor['stanar']}
                    </td>
                    <td>
                        {$ugovor['opis']}
                    </td>
                    <td>
                        {$ugovor['status']}
                    </td>
                    <td>
                        {if $ugovor['status'] == "čeka odobrenje"}
                            <a href='?potvrdi={$ugovor['id_ugovor']}'>Potvrdi</a>
                            <a href='?odbij={$ugovor['id_ugovor']}'>Odbij</a>
                        {/if}
                        {if $ugovor['status'] == "odobreno"}
                            <a href='?najam={$ugovor['id_ugovor']}'>Kreiraj najamninu</a>
                        {/if}
                    </td>
                </tr>
            {/foreach}
        </tbody>
    </table>
</section>
