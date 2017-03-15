<?php

/** 
 * Classe d'accès aux données. 
 
 * Utilise les services de la classe PDO
 * Les attributs sont tous statiques,
 * les 4 premiers pour la connexion
 * $monPdo de type PDO 
 * $monPdoBdd qui contiendra l'unique instance de la classe
 
 * @link       http://www.php.net/manual/fr/book.pdo.php
 */

class PdoBdd {
    
    private static $serveur='mysql:host=mysql51-129.perso';/*mysql51-129.perso*/
    private static $bdd='dbname=xdemofrxbkdemo';/*xdemofrxbkdemo*/
    private static $user='xdemofrxbkdemo';/*xdemofrxbkdemo*/
    private static $pwd='rMB27x82c432';/*rMB27x82c432*/
    private static $monPdo;
    private static $monPdoBdd = null;


    /**
    * Constructeur privé, crée l'instance de PDO qui sera sollicitée
    * pour toutes les méthodes internes de la classe
    */
    private function __construct() {
        try {
            PdoBdd::$monPdo = new PDO(PdoBdd::$serveur.';'.PdoBdd::$bdd,PdoBdd::$user,PdoBdd::$pwd);
            PdoBdd::$monPdo->query("SET CHARACTER SET utf8");
        } catch (PDOException $e) {
            echo 'Connexion échouée : '. $e->getMessage();
        }
    }
    
    /**
     * Destructeur de l'instance de PDO
     */
    public function __destruct() {
        PdoBdd::$monPdo = null;
    }
    
    /**
    * Retourne les informations d'un utilisateur
    * @param $login 
    * @param $mdp
    * @return infos utilisateur
    */
    public function getUtilisateur($user, $pwd){
            $req = "select id, nom from montaner_utilisateur where login='$user' and mdp='$pwd'";
            $rs = PdoBdd::$monPdo->query($req);
            $ligne = $rs->fetch(PDO::FETCH_ASSOC);
            return $ligne;
    }
    
    /**
    * Fonction statique qui crée l'unique instance de la classe

    * Appel : $pdo = PdoBdd::getPdoBdd();

    * @return l'unique objet de la classe PdoBdd
    */
   public static function getPdoBdd() {
       if (PdoBdd::$monPdoBdd == null) {
           PdoBdd::$monPdoBdd = new PdoBdd();
       }
       return PdoBdd::$monPdoBdd;
   }

   /**
     * Retourne les types
     * @return objet $lesTypes
     */
    public function getTypes(){
        $req = "SELECT * FROM montaner_type ORDER BY nom";
        $rs = PdoBdd::$monPdo->query($req);
        $lesTypes = $rs->fetchAll(PDO::FETCH_ASSOC);
        return $lesTypes;
    }
    
    /**
     * Retourne les domaines
     * @return objet $lesDomaines
     */
    public function getDomaines(){
        $req = "SELECT * FROM montaner_domaine ORDER BY nom";
        $rs = PdoBdd::$monPdo->query($req);
        $lesDomaines = $rs->fetchAll(PDO::FETCH_ASSOC);
        return $lesDomaines;
    }
    
    /**
     * Retourne le domaine
     * @return objet $domaine
     */
    public function getDomaine($id){
        $req = "SELECT * FROM montaner_domaine WHERE idDomaine = '$id'";
        $rs = PdoBdd::$monPdo->query($req);
        $dom = $rs->fetch(PDO::FETCH_ASSOC);
        return $dom;
    }
    
    /**
     * Retourne la région
     * @param $id
     * @return objet $region
     */
    public function getRegion($id){
        $req = "SELECT * FROM montaner_region WHERE idRegion = '$id'";
        $rs = PdoBdd::$monPdo->query($req);
        $reg = $rs->fetch(PDO::FETCH_ASSOC);
        return $reg;
    }
    
    /**
     * Retourne les régions
     * @return objet $lesRegions
     */
    public function getRegions(){
        $req = "SELECT * FROM montaner_region ORDER BY nom";
        $rs = PdoBdd::$monPdo->query($req);
        $lesRegions = $rs->fetchAll(PDO::FETCH_ASSOC);
        return $lesRegions;
    }
   
    /**
     * Retourne les caractéristiques de chaque vin publié
     * @return objet $lesVins
     */
    public function getVins(){
        $req = "SELECT montaner_vin.idVin as idVin, montaner_vin.nom as nom, montaner_vin.idDomaine, montaner_domaine.nom as domaine, 
                       montaner_vin.idRegion as idRegion, montaner_region.nom as region, montaner_vin.idType as idCouleur, 
                       montaner_type.nom as couleur, montaner_vin.photo, descriptif, motsCles, annee
                FROM montaner_vin JOIN montaner_domaine USING (idDomaine)
                JOIN montaner_region ON montaner_vin.idRegion = montaner_region.idRegion
                JOIN montaner_type ON montaner_vin.idType = montaner_type.idType
                WHERE publie = true
                ORDER BY region, nom;";
        $rs = PdoBdd::$monPdo->query($req);
        $lesVins = $rs->fetchAll(PDO::FETCH_ASSOC);
        return $lesVins;
    }
    
    /**
     * Retourne les caractéristiques de tous les vins de la bdd
     * @return objet $lesVins
     */
    public function getListeVins(){
        $req = "SELECT montaner_vin.idVin as idVin, montaner_vin.nom as nom, montaner_vin.idDomaine, montaner_domaine.nom as domaine, 
                       montaner_vin.idRegion as idRegion, montaner_region.nom as region, montaner_vin.idType as idCouleur, montaner_type.nom as couleur, montaner_vin.photo, descriptif, motsCles, annee
                FROM montaner_vin JOIN montaner_domaine USING (idDomaine)
                JOIN montaner_region ON montaner_vin.idRegion = montaner_region.idRegion
                JOIN montaner_type ON montaner_vin.idType = montaner_type.idType
                ORDER BY nom;";
        $rs = PdoBdd::$monPdo->query($req);
        $lesVins = $rs->fetchAll(PDO::FETCH_ASSOC);
        return $lesVins;
    }
    
    /**
     * Retourne les vins selon les critères de tris
     * @return objet $lesVins
     */
    public function getVinsTries($where){
        $req = "SELECT montaner_vin.idVin as idVin, montaner_vin.nom as nom, montaner_vin.idDomaine, montaner_domaine.nom as domaine, 
                       montaner_vin.idRegion as idRegion, montaner_region.nom as region, montaner_vin.idType as idCouleur, montaner_type.nom as couleur, montaner_vin.photo, descriptif, motsCles, annee
                FROM montaner_vin JOIN montaner_domaine USING (idDomaine)
                JOIN montaner_region ON montaner_vin.idRegion = montaner_region.idRegion
                JOIN montaner_type ON montaner_vin.idType = montaner_type.idType ";
        if(strlen($where) > 6){
            $req .= $where;
        }
        $req .= " ORDER BY nom;";
        $rs = PdoBdd::$monPdo->query($req);
        $lesVinsTries = $rs->fetchAll(PDO::FETCH_ASSOC);
        return $lesVinsTries;
    }
    
    
    /**
     * Retourne les caractéristiques du vin 
     * @return objet $vin
     */
    public function getVin($id){
        $req = "SELECT montaner_vin.idVin as idVin, montaner_vin.nom as nom, montaner_vin.idDomaine, montaner_domaine.nom as domaine, 
                       montaner_vin.idRegion as idRegion, montaner_region.nom as region, montaner_vin.idType as idCouleur, montaner_type.nom as couleur, 
                       montaner_vin.photo, descriptif, motsCles, annee, publie
                FROM montaner_vin JOIN montaner_domaine USING (idDomaine)
                JOIN montaner_region ON montaner_vin.idRegion = montaner_region.idRegion
                JOIN montaner_type ON montaner_vin.idType = montaner_type.idType
                WHERE montaner_vin.idVin = '$id';";
        $rs = PdoBdd::$monPdo->query($req);
        $vin = $rs->fetch(PDO::FETCH_ASSOC);
        return $vin;
    }
    
    /**
     * Retourne les prix suivant les différents conditionnements d'un vin
     * @return objet $lesInfosVin
     */
    public function getConditionnementVin(){
       $req = "SELECT idVin, idCond, conditionnement, volume, prix, ref, round(prix*montaner_tva.taux,3) as prixTTC
               FROM montaner_conditionnement_vin JOIN montaner_vin USING (idVin) 
               JOIN montaner_tva ON tva = idTva
               WHERE publie = true
               ORDER BY ref;";
       $rs = PdoBdd::$monPdo->query($req);
       $lesCondtVin = $rs->fetchAll(PDO::FETCH_ASSOC);
       return $lesCondtVin;
   }
   
   /**
     * Retourne les prix suivant les différents conditionnements du vin passé en paramètre
     * @return objet $lesCondtVin
     */
    public function getConditionnement($id){
       $req = "SELECT idVin, idCond, conditionnement, volume, prix, ref, round(prix*montaner_tva.taux,3) as prixTTC
               FROM montaner_conditionnement_vin JOIN montaner_vin USING (idVin)
               JOIN montaner_tva ON tva = idTva
               WHERE montaner_vin.idVin = '$id'
               ORDER BY idCond;";
       $rs = PdoBdd::$monPdo->query($req);
       $lesCondtVin = $rs->fetchAll(PDO::FETCH_ASSOC);
       return $lesCondtVin;
   }
   
   public function getConditionnementRef($idpremium){
       $req = "SELECT condReference, ref, prix
               FROM montaner_premium
               JOIN montaner_conditionnement_premium On idCond = condReference
               WHERE montaner_premium.idPremium = '$idpremium';";
       $rs = PdoBdd::$monPdo->query($req);
       $condRef = $rs->fetch(PDO::FETCH_ASSOC);
       return $condRef;
   }

/**
     * Retourne les conditionnements disponibles pour les vins
     * @return objet $lesCondts
     */
    public function getConditionnementVinGeneral(){
       $req = "SELECT * 
               FROM `montaner_conditionnement` 
               WHERE idConditionnement <= 600";
       $rs = PdoBdd::$monPdo->query($req);
       $lesCondts = $rs->fetchAll(PDO::FETCH_ASSOC);
       return $lesCondts;
   }
   
   /**
    * Retourne l'offre passée en paramètre
    * @param $id
    * @return $offre
    */
   public function getOffre($id){
        $req = "SELECT * FROM montaner_offres_speciales
                WHERE idOffre = '$id'";
        $rs = PdoBdd::$monPdo->query($req);
        $offre = $rs->fetch(PDO::FETCH_ASSOC);
        return $offre;
    }
   
   /**
    * Met à jour la table vin
    * @param $idvin
    * @param $nom
    * @param $iddom
    * @param $idreg
    * @param $idtype
    * @param $photo
    * @param $publie
    * @param $descriptif
    * @param $motsCles
    * @param $annee
    * @return etat de la requête
    */
    public function updateVin($idvin, $nom, $iddom, $idreg, $idtype, $photo, $publie, $descriptif, $motsCles, $annee){
        $req = "UPDATE montaner_vin 
                SET nom = '$nom', idDomaine = '$iddom', idRegion = '$idreg', 
                    idType = '$idtype', photo = '$photo', descriptif = '$descriptif', 
                    motsCles = '$motsCles', annee = '$annee', publie = '$publie' 
                WHERE idVin = '$idvin';";
        $stmt = PdoBdd::$monPdo->prepare($req);
        $etat = $stmt->execute();
        return $etat;
    }
    
    /**
     * Met à jour la table biere
     * @param $idbiere
     * @param $nom
     * @param $contenance
     * @param $taux
     * @param $prix
     * @param $photo
     * @param $descriptif
     * @param $publie
     * @param $idpays
     * @param $idcouleur
     * @param $idpresentation
     * @return etat de la requete
     */
    public function updateBiere($idbiere, $nom, $contenance, $taux, $prix, $photo, $descriptif, $publie, $idpays, $idcouleur, $idpresentation){
        $req = "UPDATE montaner_biere
                SET `nom`= '$nom',`contenance`= '$contenance',`taux`= $taux,
                `prix`= $prix,`photo`= '$photo',`description`= '$descriptif',`publie`= $publie,
                `pays`= '$idpays',`couleur`= $idcouleur,`tva`= 1,`idPresentation`= $idpresentation
                WHERE idBiere = '$idbiere';";
        $stmt = PdoBdd::$monPdo->prepare($req);
        $etat = $stmt->execute();
        return $etat;
    }
    
    /**
     * Met à jour la table conditionnement vin
     * @param $idvin
     * @param $idconditionnement
     * @param $ref
     * @param $prix
     * @param $conditionnement
     */
    public function updateConditionnementVin($idvin, $idcond, $ref, $prix, $cond){
        $req = "INSERT INTO montaner_conditionnement_vin
                VALUES ('$idvin', '$idcond', '$idcond cl', '$ref', '$prix', '$cond', 1)";
        $stmt = PdoBdd::$monPdo->prepare($req);
        $etat = $stmt->execute();
        return $etat;
    }
    
    /**
     * Met à jour la table domaine
     * @param $iddom
     * @param $nom
     * @param $photo
     */
    public function updateDomaine($iddom, $nom, $photo){
        $req = "UPDATE montaner_domaine 
                SET nom = '$nom', photo = '$photo'
                WHERE idDomaine = '$iddom';";
        $stmt = PdoBdd::$monPdo->prepare($req);
        $etat = $stmt->execute();
        return $etat;
    }
    
    /**
     * Met à jour la table region
     * @param $idregion
     * @param $nom
     */
    public function updateRegion($idregion, $nom){
        $req = "UPDATE montaner_region 
                SET nom = '$nom'
                WHERE idRegion = '$idregion';";
        $stmt = PdoBdd::$monPdo->prepare($req);
        $etat = $stmt->execute();
        return $etat;
    }
    
    /**
     * Met à jour la table pays
     * @param $idpays
     * @param $nom
     * @return etat de la requete
     */
    public function updatePays($idpays, $nom){
        $req = "UPDATE montaner_pays
                SET nom = '$nom'
                WHERE idPays = '$idpays';";
        $stmt = PdoBdd::$monPdo->prepare($req);
        $etat = $stmt->execute();
        return $etat;
    }
    
    /**
     * Met à jour la table couleur
     * @param $idcouleur
     * @param $nom
     * @return etat de la requête
     */
    public function updateCouleur($idcouleur, $nom){
        $req = "UPDATE montaner_couleur
                SET nom = '$nom'
                WHERE idCouleur = '$idcouleur';";
        $stmt = PdoBdd::$monPdo->prepare($req);
        $etat = $stmt->execute();
        return $etat;
    }
    
    /**
     * Met à jour la table alcool
     * @param $idalcool
     * @param $nom
     * @return etat de la requête
     */
    public function updateAlcool($idalcool, $nom){
        $req = "UPDATE montaner_alcool
                SET nom = '$nom'
                WHERE idAlcool = '$idalcool';";
        $stmt = PdoBdd::$monPdo->prepare($req);
        $etat = $stmt->execute();
        return $etat;
    }
    
    /**
     * Met à jour la table présentation
     * @param $idpres
     * @param $nom
     * @return etat de la requete
     */
    public function updatePresentation($idpres, $nom, $descriptif, $photo){
        $req = "UPDATE montaner_presentation
                SET nom = '$nom', descriptif = '$descriptif', photo = '$photo'
                WHERE idPresentation = '$idpres';";
        $stmt = PdoBdd::$monPdo->prepare($req);
        $etat = $stmt->execute();
        return $etat;
    }
    
    public function updatePremium($idpremium, $nom, $taux, $photo, $description, $publie, $condReference, $idalcool){
        $req = "UPDATE montaner_premium
                SET `nom`='$nom',`taux`= '$taux',`photo`= '$photo',`description`= '$description',`publie`= '$publie',`condReference`= '$condReference',`alcool`= '$idalcool'
                WHERE idPremium = '$idpremium';";
        $stmt = PdoBdd::$monPdo->prepare($req);
        $etat = $stmt->execute();
        return $etat;
    }
    
    /**
     * Met à jour la table conditionnement_premium
     * @param $idPremium
     * @param $ref50
     * @param $prix50
     * @param $ref75
     * @param $prix75
     * @param $ref150
     * @param $prix150
     * @param $ref300
     * @param $prix300
     * @param $ref600
     * @param $prix600
     * @param $ref900
     * @param $prix900
     * @param $ref1200
     * @param $prix1200
     * @param $ref1500
     * @param $prix1500
     * @return etat de la requête
     */
    public function updateConditionnementPremium($idPremium, $ref50, $prix50, $ref75, $prix75, $ref150, $prix150, $ref300, $prix300, $ref600, $prix600, $ref900, $prix900, $ref1200, $prix1200, $ref1500, $prix1500){
        $req = "UPDATE montaner_conditionnement_premium
                SET `ref`= '$ref50',`prixHT`= '$prix50' 
                WHERE idPremium = '$idPremium' AND idCond = 50;
                UPDATE montaner_conditionnement_premium
                SET `ref`= '$ref75',`prixHT`= '$prix75' 
                WHERE idPremium = '$idPremium' AND idCond = 75;
                UPDATE montaner_conditionnement_premium
                SET `ref`= '$ref150',`prixHT`= '$prix150' 
                WHERE idPremium = '$idPremium' AND idCond = 150;
                UPDATE montaner_conditionnement_premium
                SET `ref`= '$ref300',`prixHT`= '$prix300' 
                WHERE idPremium = '$idPremium' AND idCond = 300;
                UPDATE montaner_conditionnement_premium
                SET `ref`= '$ref600',`prixHT`= '$prix600' 
                WHERE idPremium = '$idPremium' AND idCond = 600;
                UPDATE montaner_conditionnement_premium
                SET `ref`= '$ref900',`prixHT`= '$prix900' 
                WHERE idPremium = '$idPremium' AND idCond = 900;
                UPDATE montaner_conditionnement_premium
                SET `ref`= '$ref1200',`prixHT`= '$prix1200' 
                WHERE idPremium = '$idPremium' AND idCond = 1200;
                UPDATE montaner_conditionnement_premium
                SET `ref`= '$ref1500',`prixHT`= '$prix1500' 
                WHERE idPremium = '$idPremium' AND idCond = 1500;";
        $stmt = PdoBdd::$monPdo->prepare($req);
        $etat = $stmt->execute();
        return $etat;
    }
    
    public function updateOffre($idOffre, $nom, $date, $miniature, $img){
        $req = "UPDATE montaner_offres_speciales
                SET nom = '$nom', date = '$date', photo = '$miniature', img = '$img'
                WHERE idOffre = '$idOffre';";
        $stmt = PdoBdd::$monPdo->prepare($req);
        $etat = $stmt->execute();
        return $etat;
    }
   
    /**
     * Ajoute à la table vin
     * @param $nom
     * @param $iddom
     * @param $idreg
     * @param $idtype
     * @param $photo
     * @param $publie
     * @param $descriptif
     * @param $motsCles
     * @param $annee
     */
    public function insertVin($nom, $iddom, $idreg, $idtype, $photo, $publie, $descriptif, $motsCles, $annee){
        $req = "INSERT INTO montaner_vin 
                VALUES ('','$nom', '$photo', '$descriptif', '$annee',  '$motsCles', $publie, $idreg, $iddom, $idtype);";
        $stmt = PdoBdd::$monPdo->prepare($req);
        $etat = $stmt->execute();
        return $etat;
    }
    
    /**
     * Ajoute à la table conditionnement vin
     * @param $idvin
     * @param $idconditionnement
     * @param $ref
     * @param $prix
     * @param $conditionnement
     */
    public function insertConditionnementVin($idvin, $idcond, $ref, $prix, $cond){
        $req = "INSERT INTO montaner_conditionnement_vin
                VALUES ('$idvin', '$idcond', '$idcond cl', '$ref', '$prix', '$cond', 1)";
        $stmt = PdoBdd::$monPdo->prepare($req);
        $etat = $stmt->execute();
        return $etat;
    }
    
    /**
     * Ajoute à la table domaine
     * @param $nom
     * @param $photo
     */
    public function insertDomaine($nom, $photo, $presentation){
        $req = "INSERT INTO montaner_domaine 
                VALUES ('','$nom', '$photo', '$presentation');";
        $stmt = PdoBdd::$monPdo->prepare($req);
        $etat = $stmt->execute();
        return $etat;
    }
    
    /**
     * Ajoute à la table région
     * @param $nom
     */
    public function insertRegion($nom){
        $req = "INSERT INTO montaner_region 
                VALUES ('','$nom');";
        $stmt = PdoBdd::$monPdo->prepare($req);
        $etat = $stmt->execute();
        return $etat;
    }
    
    /**
     * Ajoute à la table pays
     * @param $idpays
     * @param $nom
     * @return etat de la requête
     */
    public function insertPays($idpays, $nom){
        $req = "INSERT INTO montaner_pays
                VALUES ('$idpays','$nom');";
        $stmt = PdoBdd::$monPdo->prepare($req);
        $etat = $stmt->execute();
        return $etat;
    }
    
    /**
     * Ajoute à la table couleur
     * @param $nom
     * @return etat de la requête
     */
    public function insertCouleur($nom){
        $req = "INSERT INTO montaner_couleur
                VALUES ('','$nom');";
        $stmt = PdoBdd::$monPdo->prepare($req);
        $etat = $stmt->execute();
        return $etat;
    }
    
    /**
     * Ajoute à la table bière
     * @param  $nom
     * @param  $contenance
     * @param  $taux
     * @param  $prix
     * @param  $photo
     * @param  $descriptif
     * @param  $publie
     * @param  $idpays
     * @param  $idcouleur
     * @param  $idpresentation
     * @return etat de la requête
     */
    public function insertBiere($ref, $nom, $contenance, $taux, $prix, $photo, $descriptif, $publie, $idpays, $idcouleur, $idpresentation){
        $req = "INSERT INTO montaner_biere
                VALUES ('$ref','$nom', '$contenance', '$taux', '$prix',  '$photo', '$descriptif', '$publie', '$idpays', '$idcouleur', 1, '$idpresentation');";
        $stmt = PdoBdd::$monPdo->prepare($req);
        $etat = $stmt->execute();
        return $etat;
    }
   
    /**
     * Ajoute une présentation à la table presentation
     * @param $idPresentation
     * @param $nom
     * @param $descriptif
     * @param $photo
     * @return etat de la requete
     */
    public function insertPresentation($nom, $descriptif, $photo){
        $req = "INSERT INTO montaner_presentation
                VALUES ('','$nom', '$descriptif', '$photo');";
        $stmt = PdoBdd::$monPdo->prepare($req);
        $etat = $stmt->execute();
        return $etat;
    }
    
    /**
     * Ajoute à la table premium
     * @param $nom
     * @param $taux
     * @param $photo
     * @param $descriptif
     * @param $publie
     * @param $condReference
     * @param $alcool
     * @return etat de la requête
     */
    public function insertPremium($nom, $taux, $photo, $descriptif, $publie, $condReference, $alcool){
        $req = "INSERT INTO montaner_premium
                VALUES ('','$nom', '$taux', '$photo', '$descriptif', '$publie', '$condReference', '$alcool');";
        $stmt = PdoBdd::$monPdo->prepare($req);
        $etat = $stmt->execute();
        return $etat;
    }
    
    /**
     * Ajoute à la table conditionnement_premium
     * @param $idPremium
     * @param $ref
     * @param $prix
     * @return etat de la requête
     */
    public function insertConditionnementPremium($idPremium, $idcond, $ref, $conditionnement, $prix){
        $req = "INSERT INTO montaner_conditionnement_premium
                VALUES ('$idPremium', '$idcond', '$idcond cl', '$ref', '$conditionnement', '$prix', 1);";
        $stmt = PdoBdd::$monPdo->prepare($req);
        $etat = $stmt->execute();
        return $etat;
    }
    
    /**
     * Ajoute à la table alcool
     * @param $nom
     * @return etat de la requête
     */
    public function insertAlcool($nom){
        $req = "INSERT INTO montaner_alcool
                VALUES ('','$nom');";
        $stmt = PdoBdd::$monPdo->prepare($req);
        $etat = $stmt->execute();
        return $etat;
    }
    
    /**
     * Ajoute à la table offres_speciales
     * @param $nom
     * @param $date
     * @param $miniature
     * @param $img
     * @return etat de la requête
     */
    public function insertOffre($nom, $date, $miniature, $img){
        $req = "INSERT INTO montaner_offres_speciales
                VALUES ('','$nom', '$date', '$miniature', '$img')";
        $stmt = PdoBdd::$monPdo->prepare($req);
        $etat = $stmt->execute();
        return $etat;
    }
    
   /**
     * Retourne les infos pour les fiches domaines
     * @return objet $lesInfosVin
     */
    public function getInfosFicheDomaine(){
       $req = "SELECT idDomaine, presentation, photo
               FROM montaner_domaine";
       $rs = PdoBdd::$monPdo->query($req);
       $lesInfosDomaine = $rs->fetchAll(PDO::FETCH_ASSOC);
       return $lesInfosDomaine;
   }
   
   /**
    * Retourne l'id correspondant au nom et à la catégorie passés en paramètres
    * @return $id 
    */
   public function getId($categorie, $nom){
       $cat = ucfirst($categorie);
       $req = "SELECT id$cat FROM montaner_$categorie WHERE nom = '$nom';";
       $rs = PdoBdd::$monPdo->query($req);
       $id = $rs->fetch(PDO::FETCH_ASSOC);
       $id = $id['id'.$cat];
       return $id;
   }
   
   /**
    * Supprime le vin de la table vin  et traduction_vin
    * @param $idvin
    */
   public function deleteVin($idvin){
        $req = "DELETE FROM montaner_conditionnement_vin WHERE idVin = '$idvin';
                DELETE FROM montaner_vin WHERE idVin = '$idvin';";
        $stmt = PdoBdd::$monPdo->prepare($req);
        $etat = $stmt->execute();
        return $etat;
    }
    
    /*
     * Supprime les conditionnements du vin
     * @param $idvin
     */
    public function deleteConditionnementVin($idvin){
        $req = "DELETE FROM montaner_conditionnement_vin 
                WHERE idVin = $idvin;";
        $stmt = PdoBdd::$monPdo->prepare($req);
        $etat = $stmt->execute();
        return $etat;
    }
    
    /*
     * Supprime les conditionnements du premium
     * @param type $idpremium
     * @return type
     */
    public function deleteConditionnementPremium($idpremium){
        $req = "DELETE FROM montaner_conditionnement_premium
                WHERE idPremium = $idpremium;";
        $stmt = PdoBdd::$monPdo->prepare($req);
        $etat = $stmt->execute();
        return $etat;
    }
    
                
    
    /**
     * Supprime de la table bière
     * @param $idbiere
     * @return etat de la requete
     */
    public function deleteBiere($idbiere){
        $req = "DELETE FROM montaner_biere WHERE idBiere = '$idbiere';";
        $stmt = PdoBdd::$monPdo->prepare($req);
        $etat = $stmt->execute();
        return $etat;
    }
    
    /**
     * Supprime le domaine de la table domaine
     * ou renvoi liste des vins associés au domaine
     * @param $iddom
     */
    public function deleteDomaine($iddom){
        $recherche = "SELECT nom FROM montaner_vin WHERE idDomaine = '$iddom'";
        $rs = PdoBdd::$monPdo->query($recherche);
        $vinsdomaine = $rs->fetchAll(PDO::FETCH_ASSOC);
        if ($vinsdomaine){
            return $vinsdomaine;
        }else{
            $req = "DELETE FROM montaner_domaine WHERE idDomaine = '$iddom';";
            $stmt = PdoBdd::$monPdo->prepare($req);
            $stmt->execute();
            return false;
        }
    }
    
    /**
     * Supprime la région de la table region
     * ou renvoi liste des domaines associés à la région
     * @param $idreg
     * @return boolean
     */
    public function deleteRegion($idreg){
        $recherche = "SELECT DISTINCT montaner_domaine.nom FROM montaner_vin JOIN montaner_domaine USING(idDomaine) WHERE idRegion = '$idreg'";
        $rs = PdoBdd::$monPdo->query($recherche);
        $domainesregion = $rs->fetchAll(PDO::FETCH_ASSOC);
        if ($domainesregion){
            return $domainesregion;
        }else{
            $req = "DELETE FROM montaner_region WHERE idRegion = '$idreg';";
            $stmt = PdoBdd::$monPdo->prepare($req);
            $stmt->execute();
            return false;
        }
    }
    
    /**
     * Supprime le pays de la table pays
     * ou retourne la liste des bières associées au pays
     * @param $idpays
     * @return boolean
     */
    public function deletePays($idpays){
        $recherche = "SELECT nom FROM montaner_biere WHERE pays = '$idpays'";
        $rs = PdoBdd::$monPdo->query($recherche);
        $bierespays = $rs->fetchAll(PDO::FETCH_ASSOC);
        if ($bierespays){
            return $bierespays;
        }else{
            $req = "DELETE FROM montaner_pays WHERE idPays = '$idpays';";
            $stmt = PdoBdd::$monPdo->prepare($req);
            $stmt->execute();
            return false;
        }
    }
    
    /**
     * Supprime la couleur de la table couleur
     * ou retourne la liste des bières associées à la couleur
     * @param $idcouleur
     * @return boolean
     */
    public function deleteCouleur($idcouleur){
        $recherche = "SELECT DISTINCT montaner_biere.nom FROM montaner_biere JOIN montaner_couleur ON couleur = idCouleur WHERE idCouleur = '$idcouleur'";
        $rs = PdoBdd::$monPdo->query($recherche);
        $bierescouleur = $rs->fetchAll(PDO::FETCH_ASSOC);
        if ($bierescouleur){
            return $bierescouleur;
        }else{
            $req = "DELETE FROM montaner_couleur WHERE idCouleur = '$idcouleur';";
            $stmt = PdoBdd::$monPdo->prepare($req);
            $stmt->execute();
            return false;
        }
    }
    
    /**
     * Supprime l'alcool de la table alcool
     * ou retourne la liste des premiums associés à l'alcool
     * @param $idalcool
     * @return boolean
     */
    public function deleteAlcool($idalcool){
        $recherche = "SELECT DISTINCT montaner_premium.nom FROM montaner_premium JOIN montaner_alcool ON alcool = idAlcool WHERE idAlcool = '$idalcool'";
        $rs = PdoBdd::$monPdo->query($recherche);
        $premiumsalcool = $rs->fetchAll(PDO::FETCH_ASSOC);
        if ($premiumsalcool){
            return $premiumsalcool;
        }else{
            $req = "DELETE FROM montaner_alcool WHERE idAlcool = '$idalcool';";
            $stmt = PdoBdd::$monPdo->prepare($req);
            $stmt->execute();
            return false;
        }
    }
    
    /**
     * Supprime la présentation de la table presentation
     * ou retourne la liste des bières associées à la présentation
     * @param $idpres
     * @return boolean
     */
    public function deletePresentation($idpres){
        $recherche = "SELECT DISTINCT montaner_biere.nom FROM montaner_biere JOIN montaner_presentation USING (idPresentation) WHERE idPresentation = '$idpres'";
        $rs = PdoBdd::$monPdo->query($recherche);
        $bierespresentation = $rs->fetchAll(PDO::FETCH_ASSOC);
        if ($bierespresentation){
            return $bierespresentation;
        }else{
            $req = "DELETE FROM montaner_presentation WHERE idPresentation = '$idpres';";
            $stmt = PdoBdd::$monPdo->prepare($req);
            $stmt->execute();
            return false;
        }
    }
    
    /**
     * Supprime de la table premium et conditionnement_premium
     * @param $idpremium
     * @return etat de la requête
     */
    public function deletePremium($idpremium){
        $req = "DELETE FROM montaner_conditionnement_premium WHERE idPremium = '$idpremium';
                DELETE FROM montaner_premium WHERE idPremium = '$idpremium';";
        $stmt = PdoBdd::$monPdo->prepare($req);
        $etat = $stmt->execute();
        return $etat;
    }
    
    /**
     * Supprime de la table offres_speciales
     * @param $idoffre
     * @return etat de la requête
     */
    public function deleteOffre($idoffre){
        $req = "DELETE FROM montaner_offres_speciales WHERE idOffre = '$idoffre';";
        $stmt = PdoBdd::$monPdo->prepare($req);
        $etat = $stmt->execute();
        return $etat;
    }
    
    /**
     * Retourne le chemin de la photo associé à l'id pour suppression du dossier images
     * @param $id (vin ou domaine)
     * @param $categorie 
     * @return string chemin photo
     */
    public function getPhoto($id, $categorie){
        $cat = ucfirst($categorie);
        $req = "SELECT photo FROM montaner_$categorie WHERE id$cat = $id; ";
        $rs = PdoBdd::$monPdo->query($req);
        $chemin = $rs->fetch(PDO::FETCH_ASSOC);
        return $chemin['photo'];
    }
    
    /**
     * Retourne le chemin de la photo d'une offre associée à l'id pour suppression du dossier images
     * @param $idOffre
     * @param $categorie 
     * @return string chemin photo
     */
    public function getPhotoOffre($id){
        $req = "SELECT photo FROM montaner_offres_speciales WHERE idOffre = $id; ";
        $rs = PdoBdd::$monPdo->query($req);
        $chemin = $rs->fetch(PDO::FETCH_ASSOC);
        return $chemin['photo'];
    }
    
    /**
     * Retourne le chemin de l'img associé à l'id pour suppression du dossier images
     * @param $id
     * @param $categorie 
     * @return string chemin img
     */
    public function getImg($id){
        $req = "SELECT img FROM montaner_offres_speciales WHERE idOffre = $id; ";
        $rs = PdoBdd::$monPdo->query($req);
        $chemin = $rs->fetch(PDO::FETCH_ASSOC);
        return $chemin['img'];
    }
   
   /**
    * Retourne les bières publiées
    * @return $lesBieres
    */
   public function getBieres(){
       $req = "SELECT idBiere, montaner_biere.nom as nom, contenance, montaner_biere.taux, prix, photo, description, 
               idPays, montaner_pays.nom as pays, idCouleur, montaner_couleur.nom as couleur, round(prix*montaner_tva.taux, 3) as prixTTC, idPresentation
               FROM montaner_biere JOIN montaner_pays ON pays = idPays 
               JOIN montaner_couleur ON couleur = idCouleur
               JOIN montaner_tva ON tva = idTva
               WHERE publie = 1
               ORDER BY montaner_biere.nom";
       $rs = PdoBdd::$monPdo->query($req);
       $lesBieres = $rs->fetchAll(PDO::FETCH_ASSOC);
       return $lesBieres;
   }
   
   /**
    * Retourne la biere passé en paramètre
    * @param $id
    * @return $laBiere
    */
   public function getBiere($id){
       $req = "SELECT idBiere, montaner_biere.nom as nom, contenance, montaner_biere.taux, prix, photo, description, 
               idPays, montaner_pays.nom as pays, idCouleur, montaner_couleur.nom as couleur, idPresentation, publie
               FROM montaner_biere JOIN montaner_pays ON pays = idPays 
               JOIN montaner_couleur ON couleur = idCouleur
               WHERE idBiere = $id";
       $rs = PdoBdd::$monPdo->query($req);
       $laBiere = $rs->fetch(PDO::FETCH_ASSOC);
       return $laBiere;
   }
   
   /**
    * Retourne toutes les bières
    * @return $lesBieres
    */
   public function getListeBieres(){
       $req = "SELECT idBiere, montaner_biere.nom as nom, contenance, montaner_biere.taux, prix, photo, description, 
               idPays, montaner_pays.nom as pays, idCouleur, montaner_couleur.nom as couleur, idPresentation
               FROM montaner_biere JOIN montaner_pays ON pays = idPays 
               JOIN montaner_couleur ON couleur = idCouleur
               ORDER BY montaner_biere.nom";
       $rs = PdoBdd::$monPdo->query($req);
       $lesBieres = $rs->fetchAll(PDO::FETCH_ASSOC);
       return $lesBieres;
   }
   
   /**
     * Retourne les bieres selon les critères de tris
     * @return objet $lesBieres
     */
    public function getBieresTriees($where){
        $req = "SELECT idBiere, montaner_biere.nom as nom, contenance, montaner_biere.taux, prix, photo, description, 
               idPays, montaner_pays.nom as pays, idCouleur, montaner_couleur.nom as couleur, idPresentation
               FROM montaner_biere JOIN montaner_pays ON pays = idPays 
               JOIN montaner_couleur ON couleur = idCouleur ";
        if(strlen($where) > 6){
            $req .= $where;
        }
        $req .= " ORDER BY nom;";
        $rs = PdoBdd::$monPdo->query($req);
        $lesBieresTriees = $rs->fetchAll(PDO::FETCH_ASSOC);
        return $lesBieresTriees;
    }
    
   
   /**
     * Retourne les pays
     * @return objet $lesPays
     */
    public function getPays(){
        $req = "SELECT * FROM montaner_pays ORDER BY nom";
        $rs = PdoBdd::$monPdo->query($req);
        $lesPays = $rs->fetchAll(PDO::FETCH_ASSOC);
        return $lesPays;
    }
    
    /**
     * Retourne le pays passé en paramètre
     * @return objet $lePays
     */
    public function getUnPays($id){
        $req = "SELECT * FROM montaner_pays WHERE idPays = '$id'";
        $rs = PdoBdd::$monPdo->query($req);
        $lePays = $rs->fetch(PDO::FETCH_ASSOC);
        return $lePays;
    }
    
    /**
     * Retourne les couleurs
     * @return objet $lesCouleurs
     */
    public function getCouleurs(){
        $req = "SELECT * FROM montaner_couleur ORDER BY nom";
        $rs = PdoBdd::$monPdo->query($req);
        $lesCouleurs = $rs->fetchAll(PDO::FETCH_ASSOC);
        return $lesCouleurs;
    }
    
    /**
     * Retourne la couleur passée en paramètre
     * @param $id
     * @return $laCouleur
     */
    public function getCouleur($id){
        $req = "SELECT * FROM montaner_couleur WHERE idCouleur = '$id'";
        $rs = PdoBdd::$monPdo->query($req);
        $laCouleur = $rs->fetch(PDO::FETCH_ASSOC);
        return $laCouleur;
    }
    
    /**
     * Retourne les présentations bières
     * @return etat de la requête
     */
    public function getPresentations(){
        $req = "SELECT * FROM montaner_presentation ORDER BY nom";
        $rs = PdoBdd::$monPdo->query($req);
        $lesPresentations = $rs->fetchAll(PDO::FETCH_ASSOC);
        return $lesPresentations;
    }
    
    /**
     * Retourne la présentation passée en paramètre
     * @param $id
     * @return $laPresentation
     */
    public function getPresentation($id){
        $req = "SELECT * FROM montaner_presentation WHERE idPresentation = $id";
        $rs = PdoBdd::$monPdo->query($req);
        $laPresentation = $rs->fetch(PDO::FETCH_ASSOC);
        return $laPresentation;
    }
   
   /**
    * Retourne les premiums publiés
    * @return $lesPremiums
    */
   public function getPremiums(){
       $req = "SELECT idPremium, montaner_premium.nom as nom, taux, photo, description, idAlcool, montaner_alcool.nom as alcool, condReference
               FROM montaner_premium 
               JOIN montaner_alcool ON alcool = idAlcool
               WHERE publie = 1
               ORDER BY nom";
       $rs = PdoBdd::$monPdo->query($req);
       $lesPremiums = $rs->fetchAll(PDO::FETCH_ASSOC);
       return $lesPremiums;
   }
   
   /**
    * Retourne le premium passé en paramètre
    * @param $id
    * @return $lePremium
    */
   public function getPremium($id){
       $req = "SELECT idPremium, montaner_premium.nom as nom, taux, photo, description, idAlcool, montaner_alcool.nom as alcool, condReference, publie
               FROM montaner_premium 
               JOIN montaner_alcool ON alcool = idAlcool
               WHERE idPremium = $id";
       $rs = PdoBdd::$monPdo->query($req);
       $lePremium = $rs->fetch(PDO::FETCH_ASSOC);
       return $lePremium;
   }
   
   /**
    * Retourne tous les premiums
    * @return $lesPremiums
    */
   public function getListePremiums(){
       $req = "SELECT idPremium, montaner_premium.nom as nom, taux, photo, description, idAlcool, montaner_alcool.nom as alcool, condReference
               FROM montaner_premium 
               JOIN montaner_alcool ON alcool = idAlcool
               ORDER BY nom";
       $rs = PdoBdd::$monPdo->query($req);
       $lesPremiums = $rs->fetchAll(PDO::FETCH_ASSOC);
       return $lesPremiums;
   }
   
   public function getPremiumsTries($where){
        $req = "SELECT idPremium, montaner_premium.nom as nom, idAlcool, montaner_alcool.nom as alcool
               FROM montaner_premium 
               JOIN montaner_alcool ON alcool = idAlcool ";
        if(strlen($where) > 6){
            $req .= $where;
        }
        $req .= " ORDER BY nom;";
        $rs = PdoBdd::$monPdo->query($req);
        $lesPremiumsTries = $rs->fetchAll(PDO::FETCH_ASSOC);
        return $lesPremiumsTries;
    }
    
    public function getConditionnementPremiumGeneral(){
       $req = "SELECT * 
               FROM `montaner_conditionnement`";
       $rs = PdoBdd::$monPdo->query($req);
       $lesCondts = $rs->fetchAll(PDO::FETCH_ASSOC);
       return $lesCondts;
   }
   
   /**
    * Retourne tous les alcools
    * @return $lesAlcools
    */
   public function getAlcools(){
       $req = "SELECT * FROM montaner_alcool
               ORDER BY nom";
       $rs = PdoBdd::$monPdo->query($req);
       $lesAlcools = $rs->fetchAll(PDO::FETCH_ASSOC);
       return $lesAlcools;
   }
   
   /**
    * Retourne l'alcool passé en paramètre
    * @param $id
    * @return $alcool
    */
   public function getAlcool($id){
       $req = "SELECT * FROM montaner_alcool WHERE idAlcool = $id";
       $rs = PdoBdd::$monPdo->query($req);
       $alcool = $rs->fetch(PDO::FETCH_ASSOC);
       return $alcool;
   }
   
   /**
    * Retourne les prix du premium passé en paramètre
    * @return $lesPremiums
    */
   public function getConditionnementPremium($id){
       $req = "SELECT idPremium, idCond, conditionnement, ref, volume, prix
               FROM montaner_premium 
                    JOIN montaner_conditionnement_premium USING (idPremium)
               WHERE idPremium = $id
               ORDER BY idCond";
       $rs = PdoBdd::$monPdo->query($req);
       $lesPremiums = $rs->fetchAll(PDO::FETCH_ASSOC);
       return $lesPremiums;
   }
   
   public function getConditionnementsPremiums(){
       $req = "SELECT montaner_conditionnement_premium.idPremium, idCond, conditionnement, ref, volume, prix, 
               round(prix*montaner_tva.taux, 3) as prixTTC 
               FROM montaner_premium 
               JOIN montaner_conditionnement_premium USING (idPremium) 
               JOIN montaner_tva ON tva = idTva 
               ORDER BY idCond";
       $rs = PdoBdd::$monPdo->query($req);
       $lesPremiums = $rs->fetchAll(PDO::FETCH_ASSOC);
       return $lesPremiums;
   }
   
   /**
     * Retourne les infos pour les fiches presentation des bieres
     * @return objet $lesInfosBieres
     */
    public function getInfosFichePresentation(){
       $req = "SELECT idPresentation, nom, descriptif, photo
               FROM montaner_presentation";
       $rs = PdoBdd::$monPdo->query($req);
       $lesInfosPresentation = $rs->fetchAll(PDO::FETCH_ASSOC);
       return $lesInfosPresentation;
   }
   
   /**
    * Retourne les offres
    * @return $lesOffres
    */
   public function getOffres(){
       $req = "SELECT idOffre, nom, date, DATE_FORMAT(date, '%d/%m/%Y') AS datedmy, img, photo, MONTH(date) as mois, YEAR(date) as annee
               FROM montaner_offres_speciales
               ORDER BY date DESC";
       $rs = PdoBdd::$monPdo->query($req);
       $lesOffres = $rs->fetchAll(PDO::FETCH_ASSOC);
       return $lesOffres;
   }
   
   public function getOffresTriees($where){
        $req = "SELECT idOffre, nom, date, DATE_FORMAT(date, '%d/%m/%Y') AS datedmy, img, photo, MONTH(date) as mois, YEAR(date) as annee
                FROM montaner_offres_speciales ";
        if(strlen($where) > 6){
            $req .= $where;
        }
        $req .= " ORDER BY date DESC;";
        $rs = PdoBdd::$monPdo->query($req);
        $lesOffresTriees = $rs->fetchAll(PDO::FETCH_ASSOC);
        return $lesOffresTriees;
    }
   
   public function getDatesOffres(){
       $req = "SELECT DISTINCT MONTH(date) as mois, YEAR(date) as annee
               FROM montaner_offres_speciales
               ORDER BY date DESC";
       $rs = PdoBdd::$monPdo->query($req);
       $lesDates = $rs->fetchAll(PDO::FETCH_ASSOC);
       return $lesDates;
   }
   
   
    
   
}
