<script>
    let user, password;
    function login(user, password){
        console.log("User: " + user)
        console.log("Passwort: " + password)
    }
</script>
<input bind:value={user} placeholder="Benutzer"/>
<input type:password bind:value={password} placeholder="Passwort"/>
<a on:click={login(user, password)}>LOGIN</a>