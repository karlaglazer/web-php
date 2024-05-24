<section class="prijava">
    <form novalidate id="registracija" method="post" action="">
        <table>
            <tr>
                <td>
                    <label for="ime">Ime: </label>
                </td>
                <td>
                    <input name="ime" id="ime">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="prezime">Prezime: </label>
                </td>
                <td>
                    <input name="prezime" id="prezime">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="korisnickoIme">Korisničko ime: </label>
                </td>
                <td>
                    <input name="korisnickoIme" id="korisnicko_ime">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="email">Email: </label>
                </td>
                <td>
                    <input name="email" id="email" type="email">
                </td>
            </tr>  

            <tr>
                <td>
                    <label for="adresa">Adresa: </label>
                </td>
                <td>
                    <input name="adresa" id="adresa">
                </td>
            </tr>  
            <tr>
                <td>
                    <label for="lozinka">Lozinka: </label>
                </td>
                <td>
                    <input name="lozinka" type="password" id="lozinka">
                </td>
            </tr>  
            <tr>
                <td>
                    <label for="potvrda">Potvrda lozinke: </label>
                </td>
                <td>
                    <input name="potvrda" type="password" id="potvrda">
                </td>
            </tr>  
        </table>
        <div class="g-recaptcha" 
             data-sitekey="6LffWjkiAAAAAAZ4RDVCiyTDKKg6DZlbLN_2vl4v">
        </div>
        <br>
        <button type="submit" id="posalji">Pošalji</button>
    </form>
</section>
