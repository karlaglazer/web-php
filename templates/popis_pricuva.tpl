<section>
    <ul>
        {foreach from=$nekretnine item=nekretnina}
            <li>
                <h3>{$nekretnina['opis']}</h3>
                <div>
                    <h4>Pričuve</h4>
                    <div >
                        <form method='post'>
                            <input name='id' value='{$nekretnina['id_nekretnina']}' hidden>
                            <label for='datum'>Mjesec: </label>
                            <input type="date" name='datum' required><br>
                            <label for='cijena'>Cijena: </label>
                            <input type="number" name ='cijena' required>
                            <button type='submit'>Spremi</button>
                        </form>
                    </div>
                    <table>
                        <tbody>
                            {foreach $pricuve as $key=>$value}
                                {if $key == $nekretnina['id_nekretnina']}
                                    {foreach from=$value item=line}
                                        <tr>
                                            <td>
                                                {$line['mjesec']}
                                            </td>
                                            <td>
                                                {$line['cijena']}
                                            </td>
                                        </tr>
                                    {/foreach}
                                {/if}
                            {/foreach}
                        </tbody>
                    </table>
                </div>
                <div>
                    <h4>Nedostaci</h4>
                    <table>
                        <tbody>
                            {foreach $nedostaci as $key=>$value}
                                {if $key == $nekretnina['id_nekretnina']}
                                    {foreach from=$value item=line}
                                        <tr>
                                            <td>
                                                {$line['nedostatak']}
                                            </td>
                                            <td>
                                                {$line['vaznost']}
                                            </td>
                                            <td>
                                                {$line['status']}
                                            </td>
                                            <td>
                                                {if $line['status']=="neće se razriješiti"}
                                                    {$line['razlog']}
                                                {/if}
                                            </td>
                                            {if $line['status']=="na čekanju"}
                                                <td>
                                                    <form method="post">
                                                        <input name ="id_nedostatak" value="{$line['id_nedostatak_nekretnine']}" style='display:none'>
                                                        <input type="radio" name='status' value='da' checked><label>riješen</label>
                                                        <input type="radio" name='status' value='ne'><label>neće se razriješiti</label> |
                                                        <label for='razlog'>Razlog: </label>
                                                        <input name='razlog'>
                                                        <button type="submit">Spremi</button>
                                                    </form>
                                                </td>
                                            {/if}
                                        </tr>
                                    {/foreach}
                                {/if}
                            {/foreach}
                        </tbody>
                    </table>
                </div>
            </li>
        {/foreach}
    </ul>
</section>
