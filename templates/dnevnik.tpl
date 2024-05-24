<h2>{$naziv}</h2>
<section class="popis">
    <form method="post">
        <input name='filterDatum' style="display:none">
        <label for="od">Od: </label>
        <input type="date" name="od" value="{$od}">
        <label for="do">Do: </label>
        <input type="date" name="do" value="{$do}">
        <button type="submit">Filtriraj</button>
    </form>
    <table>
        <thead>
            <tr>
                <th>
                    Vrijeme
                </th>
                <th>
                    Korisnik
                </th>
                <th>
                    Tip rada
                </th>
                <th>
                    Radnja
                </th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$dnevnik item=log}
                <tr>
                    <td>{$log[0]}</td>
                    <td>{$log[1]}</td>
                    <td>{$log[2]}</td>
                    <td>{$log[3]}</td>
                </tr>
            {/foreach}
        </tbody>
    </table>
</section>
