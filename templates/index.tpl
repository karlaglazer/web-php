<section>
    <h2>Upravljanje nekretninama</h2>
    {if $kolacic==true}
        <div>
            <form method="post">
                <label>Prihvaćam uvjete korištenja</label>
                <button type="submit">Prihvati</button>
            </form>
        </div>
    {else}
        <div>
            <h3><a href='{$putanja}/dokumentacija.html'>Dokumentacija</a></h3>
            <h3><a href='{$putanja}/o_autoru.html'>O autoru</a></h3>
        </div>
    {/if}

</section>