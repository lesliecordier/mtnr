            <div class="backoffice">
                
                <div class="centre">
                    <a href="admin.php?control=offres&action=gestion"><img title="Retour" src="images/logo.jpg"/></a><br/><br/>
                </div>
                
                <div class="container-fluid">
                    <span class="centre"><h3>Fiche de l'offre spéciale</h3></span><br/>
                    <form method="POST" action="admin.php?control=offres&action=update" enctype="multipart/form-data" role="form" data-toggle="validator">
                        
                        <!-- Nom -->
                        <div class="form-group">
                            <label for="nomoffre" class="control-label text-left">Nom :</label>
                            <div class="form-group-lg">
                                <input type="text" class="form-control" name="nomoffre" id="nomoffre" value="<?php echo $offre['nom']; ?>" required>
                                <input type="hidden" name="idOffre" value="<?php echo $offre['idOffre'];?>">
                            </div>
                            <br/>
                        </div>
                        
                        <!-- Date -->
                        <div class="form-group">
                            <label for="date" class="control-label text-left">Date :</label>
                            <div class="form-group-lg" >
				<input type="text" name="date" class="form-control datepicker" value="<?php echo $offre['date']; ?>" data-date-format="dd-mm-yyyy" readonly>
                            </div>
                            <br/>
                        </div>
                        
                        <!-- Fichier -->
                        <table class="upload">
                            <tr>
                                <td id="td1">
                                    <div class="form-group">
                                        <div class="form-group-lg">
                                            <label  class="control-label" for="pdf">Fichier JPG :</label>
                                            <input type="file" name="img" id="img" accept="image/*" value="<?php echo $offre['img']; ?>">
                                            <input type="hidden" name="imgExistante" accept="image/*" value="<?php echo $offre['img'];?>">
                                        </div> 
                                    </div>
                                </td>
                                <td id="td2">
                                    <div>
                                        <a href="#" class="pop" data-toggle="modal" data-target="#imgmodal">
                                            <img id="apercuimg" src="<?php echo $offre['img'] ?>"/>
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
                                            <input type="file" name="miniature" id="photo" accept="image/*" value="<?php echo $offre['photo'];?>">
                                            <input type="hidden" name="miniatureExistante" accept="image/*" value="<?php echo $offre['photo'];?>">
                                        </div> 
                                    </div>
                                </td>
                                <td id="td2">
                                    <div>
                                        <a href="#" class="pop" data-toggle="modal" data-target="#myModal">
                                            <img id="apercuphoto" src="<?php echo $offre['photo'] ?>"/>
                                        </a>
                                    </div> 
                                </td>
                            </tr>
                        </table>
                        <br/>

                        <div class="form-group">
                            <br/>
                            <button class="btn btn-info btn-lg btn-block" type="submit" data-disable="false">Valider</button>
                        </div>

                    </form>
                    <form method="post" action="admin.php?control=offres&action=delete">
                        <input type="hidden" name="idOffre" value="<?php echo $offre['idOffre'];?>"/>
                        <input type="hidden" name="nomoffre" value="<?php echo $offre['nom'];?>"/>
                        <button class="btn btn-danger btn-lg btn-block delete" type="submit">Supprimer</button>
                    </form>
                
                
                </div>
        
                <!-- Affiche image en grand -->
                <div class="modal fade" id="imgmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <button type="button" class="close btn-lg" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <img src="<?php echo $offre['img'];?>"  style="width: 90%" >
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
                                <img src="<?php echo $offre['photo'];?>"  style="width: 90%" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>


