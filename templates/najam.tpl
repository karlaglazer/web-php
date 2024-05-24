<section>
    <h2>Najam</h2>
    <form method="post" enctype="multipart/form-data">
        {foreach from=$slobodne item=nekretnina}
            <input type="checkbox" value="{$nekretnina['id_nekretnina']}" name="{$nekretnina['opis']}nekretnina"><label>{$nekretnina['opis']}</label><br>
            {/foreach}
        <input type="file" name='slika' required><br>
        <button type="submit">Unajmi</button>
    </form>
        <div>
            <h3>
            Popis mojih ugovora
        </h3>
        <table>
            {foreach from=$ugovori item=ugovor}
                <tr>
                    <td>
                        <img src="{$ugovor['slika_stanara']}" width=50px height=50px/>
                    </td>
                    <td><img src="{$ugovor['slika']}" width=50px height=50px/></td>
                    <td>
                        {$ugovor['opis']}
                    </td>
                    <td>
                        {$ugovor['status']}
                    </td>
                </tr>
                {/foreach}
        </table>
        </div>
        
    <div>
        <h3>
            Popis unajmljenih
        </h3>
        <ul>
            {foreach from=$iznajmljene item=nekretnina}
                <li><a href="nekretnina.php?id_nekretnina={$nekretnina['id_nekretnina']}&stanar={$stanar}">{$nekretnina['opis']}</a></li>
                {/foreach}
        </ul>
    </div>
</section>