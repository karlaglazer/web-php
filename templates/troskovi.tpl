<section>
    <h2>Pricuve</h2>
    <div class="popis">
        <table>
            <thead>
                <tr>
                    <th>
                        Nekretnina
                    </th>
                    <th>
                        Datum
                    </th>
                    <th>
                        Cijena
                    </th>
                    <th>
                        Placeno
                    </th>
                    <th>
                    </th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$pricuve item=pricuva}
                    <tr>
                        <td>
                            {$pricuva['opis']}
                        </td>
                        <td>
                            {$pricuva['mjesec']}
                        </td>
                        <td>
                            {$pricuva['cijena']}
                        </td>
                        <td>
                            {if $pricuva['placeno']==null}
                                ne
                            {else}
                                da
                            {/if}
                        </td>
                        <td>
                            {if $pricuva['placeno'] == null}
                                <form method='post'>
                                    <input value='{$pricuva['id_pricuva']}' name='platiPricuvu' hidden>
                                    <button type="submit">plati</button>
                                </form>
                            {/if}
                        </td>
                    </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
    <h2>Najam</h2>
    <div class="popis">
        <table>
            <thead>
                <tr>
                    <th>
                        Nekretnina
                    </th>
                    <th>
                        Datum
                    </th>
                    <th>
                        Cijena
                    </th>
                    <th>
                        Placeno
                    </th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$najamnine item=najam}
                    <tr>
                        <td>
                            {$najam['opis']}
                        </td>
                        <td>
                            {$najam['mjesec']}
                        </td>
                        <td>
                            {$najam['cijena']}
                        </td>
                        <td>
                            {if $najam['placeno']==null}
                                ne

                            {else}
                                da
                            {/if}
                        </td>
                        <td>
                            {if $najam['placeno'] == null}
                                <form method='post'>
                                    <input value='{$najam['id_zaduzenja_najam']}' name='platiNajam' hidden>
                                    <button type="submit">plati</button>
                                </form>
                            {/if}
                        </td>
                    </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
</section>
