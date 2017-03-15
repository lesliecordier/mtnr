
            <div id="login" class="centre" data-url="login">
             
                <div>
                    <img src="images/logo.jpg"/>
                    <h3>Bienvenue dans votre backoffice</h3>
                    <p>Veuillez entrer votre nom et mot de passe</p><br/><br/>
                    
                    <div>
                        <form class="form-horizontal" role="form" name="frmLogin" method="POST" action="index.php?control=login&action=valide">
                            <div class="form-group">
                                <input type="text" class="form-control" id="user" name="user" placeholder="Nom d'utilisateur">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" name="password" id="password" placeholder="Mot de passe" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-lg btn-block">Valider</button>
                            </div>
                        </form>
                    </div>
                </div>
                
            </div>
        
           