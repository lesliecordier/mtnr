<div class="backoffice">

    <div class="centre">
        <img src="images/logo.jpg"/><br/><br/>
    </div>

    <div id="sommaire" class="container-fluid">
        <a href="admin.php?control=vins&action=sommaire" id="vins" class="btn btn-lg btn-info btn-block">Les Vins</a>
        <a href="admin.php?control=offres&action=gestion" id="regions" class="btn btn-lg btn-info btn-block">Les Offres Spéciales</a>
    </div>

    <div>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs nav-justified" role="tablist">
            <li role="presentation" class="active"><a href="#import" aria-controls="home" role="tab" data-toggle="tab">Import</a></li>
            <li role="presentation"><a href="#export" aria-controls="profile" role="tab" data-toggle="tab">Export</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="import">
                <form enctype="multipart/form-data"  class="form-horizontal" method="post" action="index.php?control=import&action=xls">
                    <div class="form-group">
                        <label class="col-md-4" for="import-xls">Importer un nouveau fichier vins.xls</label>
                        <div class="col-md-8">
                            <input type="file" name="import-xls" id="import-xls">
                            <p class="help-block">Attention, les données actuelles seront écrasées par ces nouvelles données.</p>
                            <?php if (!empty($message1)) echo "<div class=\"alert alert-success col-md-7\">$message1</div>" ?>
                            <?php if (!empty($erreur1)) echo "<div class=\"alert alert-danger col-md-7\">$erreur1</div>" ?>
                        </div>
                    </div>
                    <div class="col-md-4"></div>
                    <button type="submit" class="btn btn-default">Envoyer</button>
                </form>
                <form enctype="multipart/form-data"  class="form-horizontal" method="post" action="index.php?control=import&action=doc">
                    <div class="form-group">
                        <label class="col-md-4"  for="import-doc">Télécharger un fichier PDF ou une image JPEG</label>
                        <div class="col-md-8">
                            <input type="file" name="import-doc" id="import-doc">
                            <p class="help-block">Le fichier doit avoir le même nom que dans le fichier vins.xls afin d'être correctement associé au vin.</p>
                            <?php if (!empty($message2)) echo "<div class=\"alert alert-success col-md-7\">$message2</div>" ?>
                            <?php if (!empty($erreur2)) echo "<div class=\"alert alert-danger col-md-7\">$erreur2</div>" ?>
                        </div>
                    </div>
                    <div class="col-md-4"></div>
                    <button type="submit" class="btn btn-default">Envoyer</button>
                </form>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="export">...</div>
        </div>
    </div>

</div>
