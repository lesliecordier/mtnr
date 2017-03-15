            <div class="backoffice">
                
                <div class="centre">
                    <a href="admin.php?control=offres&action=gestion"><img title="Retour" src="images/logo.jpg"/></a><br/><br/>
                </div>
                
                <div class="container-fluid">
                    <span class="centre"><h3>Fiche de l'offre spéciale</h3></span><br/>
                    <form method="POST" action="admin.php?control=offres&action=add" enctype="multipart/form-data" role="form" data-toggle="validator">
                        
                        <!-- Nom -->
                        <div class="form-group">
                            <label for="nomoffre" class="control-label text-left">Nom :</label>
                            <div class="form-group-lg">
                                <input type="text" class="form-control" name="nomoffre" id="nomoffre" value="" required autofocus="autofocus" >
                            </div>
                            <br/>
                        </div>
                        
                        <!-- Date -->
                        <div class="form-group">
                            <label for="date" class="control-label text-left">Date :</label>
                            <div class="form-group-lg" >
                                <input type="text" name="date" class="form-control datepicker" value="<?php echo date('d-m-Y'); ?>" data-date-format="dd-mm-yyyy" readonly required="">
                            </div>
                            <br/>
                        </div>
                        
                        <!-- Fichier -->
                        <table class="upload">
                            <tr>
                                <td id="td1">
                                    <div class="form-group">
                                        <div class="form-group-lg">
                                            <label  class="control-label" for="img">Fichier JPG :</label>
                                            <input type="file" name="img" id="img" accept="image/*" value="">
                                        </div> 
                                    </div>
                                </td>
                                <td id="td2">
                                    <div>
                                        <a href="#" class="pop" data-toggle="modal" data-target="#imgmodal">
                                            <img id="apercuimg" src=""/>
                                        </a>
                                    </div> 
                                </td>
                            </tr>
                        </table>
                        <br/>
                        
                        <!-- Miniature --> 
                        <table class="upload">
                            <tr>
                                <td id="td1">
                                    <div class="form-group">
                                        <div class="form-group-lg">
                                            <label  class="control-label" for="miniature">Image miniature :</label>
                                            <input type="file" name="miniature" id="photo" accept="image/*" value="">
                                        </div> 
                                    </div>
                                </td>
                                <td id="td2">
                                    <div>
                                        <a href="#" class="pop" data-toggle="modal" data-target="#myModal">
                                            <img id="apercuphoto" src=""/>
                                        </a>
                                    </div> 
                                </td>
                            </tr>
                        </table>
                        <br/>

                        <div class="form-group">
                            <br/>
                            <button class="btn btn-info btn-lg btn-block" type="submit" data-disable="false">Valider</button>
                            <br/>
                        </div>

                    </form>
                
                
                
                </div>
        
                <!-- Affiche image en grand -->
                <div class="modal fade" id="imgmodal" tabindex="-1" role="dialog" aria-labelledby="imgLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <button type="button" class="close btn-lg" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <img src="" style="width: 90%" >
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Affiche miniature en grand -->
                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <button type="button" class="close btn-lg" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <img src="" style="width: 90%" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>


