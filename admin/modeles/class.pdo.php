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
    
        private static $serveur='mysql:host=localhost';
        private static $bdd='dbname=montaner';
        private static $user='root';
        private static $pwd='test';
        private static $monPdo;
        private static $monPdoBdd=null;


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
        $req = "SELECT * FROM mtnr_type ORDER BY nom";
        $rs = PdoBdd::$monPdo->query($req);
        $lesTypes = $rs->fetchAll(PDO::FETCH_ASSOC);
        return $lesTypes;
    }
    
    /**
     * Retourne les domaines
     * @return objet $lesDomaines
     */
    public function getDomaines(){
        $req = "SELECT * FROM mtnr_domaine ORDER BY nom";
        $rs = PdoBdd::$monPdo->query($req);
        $lesDomaines = $rs->fetchAll(PDO::FETCH_ASSOC);
        return $lesDomaines;
    }
    
    /**
     * Retourne le domaine
     * @return objet $domaine
     */
    public function getDomaine($id){
        $req = "SELECT * FROM mtnr_domaine WHERE idDomaine = '$id'";
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
        $req = "SELECT * FROM mtnr_region WHERE idRegion = '$id'";
        $rs = PdoBdd::$monPdo->query($req);
        $reg = $rs->fetch(PDO::FETCH_ASSOC);
        return $reg;
    }
    
    /**
     * Retourne les régions
     * @return objet $lesRegions
     */
    public function getRegions(){
        $req = "SELECT * FROM mtnr_region ORDER BY nom";
        $rs = PdoBdd::$monPdo->query($req);
        $lesRegions = $rs->fetchAll(PDO::FETCH_ASSOC);
        return $lesRegions;
    }
   
    /**
     * Retourne les caractéristiques de chaque vin publié
     * @return objet $lesVins
     */
    public function getVins(){
        $req = "SELECT mtnr_vin.id as idVin, mtnr_vin.nom as nom, mtnr_vin.idDomaine, mtnr_domaine.nom as domaine, 
                       mtnr_vin.idRegion as idRegion, mtnr_region.nom as region, mtnr_vin.idType as idCouleur, 
                       mtnr_type.nom as couleur,  millesime as annee
                FROM mtnr_vin JOIN mtnr_domaine USING (idDomaine)
                JOIN mtnr_region ON mtnr_vin.idRegion = mtnr_region.idRegion
                JOIN mtnr_type ON mtnr_vin.idType = mtnr_type.idType
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
        $req = "SELECT mtnr_vin.id as idVin, mtnr_vin.nom as nom, mtnr_vin.idDomaine, mtnr_domaine.nom as domaine, 
                       mtnr_vin.idRegion as idRegion, mtnr_region.nom as region, mtnr_vin.idType as idCouleur, mtnr_type.nom as couleur, millesime as annee
                FROM mtnr_vin JOIN mtnr_domaine USING (idDomaine)
                JOIN mtnr_region ON mtnr_vin.idRegion = mtnr_region.idRegion
                JOIN mtnr_type ON mtnr_vin.idType = mtnr_type.idType
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
        $req = "SELECT mtnr_vin.id as idVin, mtnr_vin.nom as nom, mtnr_vin.idDomaine, mtnr_domaine.nom as domaine, 
                       mtnr_vin.idRegion as idRegion, mtnr_region.nom as region, mtnr_vin.idType as idCouleur, mtnr_type.nom as couleur, millesime as annee
                FROM mtnr_vin JOIN mtnr_domaine USING (idDomaine)
                JOIN mtnr_region ON mtnr_vin.idRegion = mtnr_region.idRegion
                JOIN mtnr_type ON mtnr_vin.idType = mtnr_type.idType ";
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
        $req = "SELECT mtnr_vin.id as idVin, mtnr_vin.nom as nom, mtnr_vin.idDomaine, mtnr_domaine.nom as domaine, 
                       mtnr_vin.idRegion as idRegion, mtnr_region.nom as region, mtnr_vin.idType as idCouleur, mtnr_type.nom as couleur, 
                       millesime as annee
                FROM mtnr_vin JOIN mtnr_domaine USING (idDomaine)
                JOIN mtnr_region ON mtnr_vin.idRegion = mtnr_region.idRegion
                JOIN mtnr_type ON mtnr_vin.idType = mtnr_type.idType
                WHERE mtnr_vin.id = '$id';";
        $rs = PdoBdd::$monPdo->query($req);
        $vin = $rs->fetch(PDO::FETCH_ASSOC);
        return $vin;
    }
    
    /**
     * Retourne les prix suivant les différents conditionnements d'un vin
     * @return objet $lesInfosVin
     */
    public function getConditionnementVin(){
       $req = "SELECT idVin, id, conditionnement, volume, prixHT, ref, round(prixHT*mtnr_tva.taux,3) as prixTTC
               FROM mtnr_conditionnement_vin 
               JOIN mtnr_tva ON tva = idTva
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
       $req = "SELECT idVin, id, conditionnement, volume, prixHT, ref, round(prixHT*mtnr_tva.taux,3) as prixTTC
               FROM mtnr_conditionnement_vin 
               JOIN mtnr_tva ON tva = idTva
               WHERE idVin = '$id'
               ORDER BY id;";
       $rs = PdoBdd::$monPdo->query($req);
       $lesCondtVin = $rs->fetchAll(PDO::FETCH_ASSOC);
       return $lesCondtVin;
   }

/**
     * Retourne les conditionnements disponibles pour les vins
     * @return objet $lesCondts
     */
    public function getConditionnementVinGeneral(){
       $req = "SELECT * 
               FROM `mtnr_conditionnement`";
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
        $req = "SELECT * FROM mtnr_offres_speciales
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
    * @param $annee
    * @return etat de la requête
    */
    public function updateVin($idvin, $nom, $iddom, $idreg, $idtype, $annee){
        $req = "UPDATE mtnr_vin 
                SET nom = '$nom', idDomaine = '$iddom', idRegion = '$idreg', 
                    idType = '$idtype', millesime = '$annee'
                WHERE id = '$idvin';";
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
    public function updateConditionnementVin($idvin, $id, $ref, $prix, $cond, $vol){
        $req = "INSERT INTO mtnr_conditionnement_vin
                VALUES ('$id', '$ref', '$prix', '$cond', '$vol', $idvin, 1)";
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
    public function updateDomaine($iddom, $nom){
        $req = "UPDATE mtnr_domaine 
                SET nom = '$nom'
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
        $req = "UPDATE mtnr_region 
                SET nom = '$nom'
                WHERE idRegion = '$idregion';";
        $stmt = PdoBdd::$monPdo->prepare($req);
        $etat = $stmt->execute();
        return $etat;
    }
    
    public function updateOffre($idOffre, $nom, $date, $miniature, $img){
        $req = "UPDATE mtnr_offres_speciales
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
     * @param $annee
     */
    public function insertVin($nom, $iddom, $idreg, $idtype, $annee){
        $req = "INSERT INTO mtnr_vin 
                VALUES ('','$nom', '$annee', $idreg, $iddom, $idtype);";
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
    public function insertConditionnementVin($idvin, $id, $ref, $prix, $cond, $vol){
        $req = "INSERT INTO mtnr_conditionnement_vin
                VALUES ('$id', '$ref', '$prix', '$cond', '$vol', '$idvin', 1)";
        $stmt = PdoBdd::$monPdo->prepare($req);
        $etat = $stmt->execute();
        return $etat;
    }
    
    /**
     * Ajoute à la table domaine
     * @param $nom
     * @param $photo
     */
    public function insertDomaine($nom){
        $req = "INSERT INTO mtnr_domaine 
                VALUES ('','$nom');";
        $stmt = PdoBdd::$monPdo->prepare($req);
        $etat = $stmt->execute();
        return $etat;
    }
    
    /**
     * Ajoute à la table région
     * @param $nom
     */
    public function insertRegion($nom){
        $req = "INSERT INTO mtnr_region 
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
        $req = "INSERT INTO mtnr_offres_speciales
                VALUES ('','$nom', '$date', '$miniature', '$img')";
        $stmt = PdoBdd::$monPdo->prepare($req);
        $etat = $stmt->execute();
        return $etat;
    }
   
   /**
    * Retourne l'id correspondant au nom et à la catégorie passés en paramètres
    * @return $id 
    */
   public function getId($categorie, $nom){
       $cat = ucfirst($categorie);
       $req = "SELECT id$cat FROM mtnr_$categorie WHERE nom = '$nom';";
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
        $req = "DELETE FROM mtnr_conditionnement_vin WHERE idVin = '$idvin';
                DELETE FROM mtnr_vin WHERE id = '$idvin';";
        $stmt = PdoBdd::$monPdo->prepare($req);
        $etat = $stmt->execute();
        return $etat;
    }
    
    /*
     * Supprime les conditionnements du vin
     * @param $idvin
     */
    public function deleteConditionnementVin($idvin){
        $req = "DELETE FROM mtnr_conditionnement_vin 
                WHERE idVin = $idvin;";
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
        $recherche = "SELECT nom FROM mtnr_vin WHERE idDomaine = '$iddom'";
        $rs = PdoBdd::$monPdo->query($recherche);
        $vinsdomaine = $rs->fetchAll(PDO::FETCH_ASSOC);
        if ($vinsdomaine){
            return $vinsdomaine;
        }else{
            $req = "DELETE FROM mtnr_domaine WHERE idDomaine = '$iddom';";
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
        $recherche = "SELECT DISTINCT mtnr_domaine.nom FROM mtnr_vin JOIN mtnr_domaine USING(idDomaine) WHERE idRegion = '$idreg'";
        $rs = PdoBdd::$monPdo->query($recherche);
        $domainesregion = $rs->fetchAll(PDO::FETCH_ASSOC);
        if ($domainesregion){
            return $domainesregion;
        }else{
            $req = "DELETE FROM mtnr_region WHERE idRegion = '$idreg';";
            $stmt = PdoBdd::$monPdo->prepare($req);
            $stmt->execute();
            return false;
        }
    }
    
    /**
     * Supprime de la table offres_speciales
     * @param $idoffre
     * @return etat de la requête
     
    public function deleteOffre($idoffre){
        $req = "DELETE FROM mtnr_offres_speciales WHERE idOffre = '$idoffre';";
        $stmt = PdoBdd::$monPdo->prepare($req);
        $etat = $stmt->execute();
        return $etat;
    }
    
    /**
     * Retourne le chemin de la photo d'une offre associée à l'id pour suppression du dossier images
     * @param $idOffre
     * @param $categorie 
     * @return string chemin photo
     
    public function getPhotoOffre($id){
        $req = "SELECT photo FROM mtnr_offres_speciales WHERE idOffre = $id; ";
        $rs = PdoBdd::$monPdo->query($req);
        $chemin = $rs->fetch(PDO::FETCH_ASSOC);
        return $chemin['photo'];
    }
    
    /**
     * Retourne le chemin de l'img associé à l'id pour suppression du dossier images
     * @param $id
     * @param $categorie 
     * @return string chemin img
     
    public function getImg($id){
        $req = "SELECT img FROM mtnr_offres_speciales WHERE idOffre = $id; ";
        $rs = PdoBdd::$monPdo->query($req);
        $chemin = $rs->fetch(PDO::FETCH_ASSOC);
        return $chemin['img'];
    }
   
   /**
    * Retourne les offres
    * @return $lesOffres
    
   public function getOffres(){
       $req = "SELECT idOffre, nom, date, DATE_FORMAT(date, '%d/%m/%Y') AS datedmy, img, photo, MONTH(date) as mois, YEAR(date) as annee
               FROM mtnr_offres_speciales
               ORDER BY date DESC";
       $rs = PdoBdd::$monPdo->query($req);
       $lesOffres = $rs->fetchAll(PDO::FETCH_ASSOC);
       return $lesOffres;
   }
   
   public function getOffresTriees($where){
        $req = "SELECT idOffre, nom, date, DATE_FORMAT(date, '%d/%m/%Y') AS datedmy, img, photo, MONTH(date) as mois, YEAR(date) as annee
                FROM mtnr_offres_speciales ";
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
               FROM mtnr_offres_speciales
               ORDER BY date DESC";
       $rs = PdoBdd::$monPdo->query($req);
       $lesDates = $rs->fetchAll(PDO::FETCH_ASSOC);
       return $lesDates;
   }
    */
   
    public function formatVolume(){
        $req = "SELECT * FROM `mtnr_conditionnement_vin` WHERE volume not LIKE '% cl'";
        $rs = PdoBdd::$monPdo->query($req);
        $lesVolumes = $rs->fetchAll(PDO::FETCH_ASSOC);
        $volumes = array();
        foreach ($lesVolumes as $unVolume){
            $vol = substr($unVolume, -2,2);
            if ($vol == 'cl'){
                $vol = substr_replace($unVolume, ' cl', -2,2);
                $volumes.push($unVolume);
            }
        }
    }
    
}
