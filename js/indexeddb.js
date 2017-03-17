/* 
 * Connect and create objects in IndexedDB
 */

DB_NAME = 'montaner-indexeddb-vins';
DB_VERSION = 1; // Use only int, not float 
DB_STORE_NAME = 'vins';

var db, objVins;
var urlPdf = [];

/**
 * Open database or create it if not exists
 */
function openDb() {
    var req = window.indexedDB.open(DB_NAME, DB_VERSION);
    console.log("openDb ...");
    req.onsuccess = function (evt) {
        db = this.result;
        objVins = getVins();
        console.log("openDb DONE");

    }
    req.onerror = function (evt) {
        console.error("openDb:", evt.target.errorCode);
    }

    req.onupgradeneeded = function (evt) {
        console.log(evt);
        // Loader
        $('#vins, #offres').css('display', 'none');
        new imageLoader(cImageSrc, 'startAnimation()');
        $('#loaderImage').css('display', 'block');

        // Create objectStores
        console.log("openDb.onupgradeneeded");
        var store = this.result;
        store = store.createObjectStore(
                DB_STORE_NAME, {keyPath: 'idVin', autoIncrement: true});
        store.createIndex('nom', 'nom', {unique: false});
        store.createIndex('region', 'region', {unique: false});
        store.createIndex('domaine', 'domaine', {unique: false});
        store.createIndex('couleur', 'couleur', {unique: false});
        console.log("store1 created");

        // Add datas "vins"
        alasql.promise('SELECT * FROM XLS("vins.xls", {headers:true})')
                .then(function (data) {
                    var transaction = evt.target.result.transaction("vins", "readwrite");
                    var vinsObjectStore = transaction.objectStore("vins");
                    for (var i in data) {
                        vinsObjectStore.add(data[i]);
                    }
                    console.log('import xls ok');
                    location.reload();
                }).catch(function (err) {
            console.log('Error:', err);
        });
    }
}


function getVins() {

    var store = db.transaction(DB_STORE_NAME).objectStore(DB_STORE_NAME);
    var req;
    var objvins = [];
    req = store.count();
    // Requests are executed in the order in which they were made against the
    // transaction, and their results are returned in the same order.
    // Thus the count text below will be displayed before the actual pub list
    // (not that it is algorithmically important in this case).
    req.onsuccess = function (evt) {
        console.log("Nb de vins:" + evt.target.result);
    };
    req.onerror = function (evt) {
        console.error("add error", this.error);
    };

    var i = 0;
    req = store.openCursor();
    req.onsuccess = function (evt) {
        var cursor = evt.target.result;

        // If the cursor is pointing at something, ask for the data
        if (cursor) {
            req = store.get(cursor.key);
            req.onsuccess = function (evt) {
                var value = evt.target.result;
                objvins[cursor.key] = {
                    idVin: cursor.key,
                    visible: true,
                    domaine: value.domaine,
                    domainemin: '',
                    nom: value.nom,
                    couleur: value.couleur,
                    couleurmin: '',
                    region: value.region,
                    regionmin: '',
                    millesime: (value.millesime) ? value.millesime : '',
                    pdf: (value.pdf) ? 'docs/' + value.pdf : '#',
                    prixRef1: {
                        ref: value.ref,
                        conditionnement: value.cond,
                        volume: value.volume,
                        prix: (value.prixHT).toFixed(3)
                    },
                    prixRef2: {
                        ref: (value.ref2) ? value.ref2 : '',
                        conditionnement: (value.cond2) ? value.cond2 : '',
                        volume: (value.volume2) ? value.volume2 : '',
                        prix: (value.prixHT2) ? (value.prixHT2).toFixed(3) : ''
                    },
                    prixRef3: {
                        ref: (value.ref3) ? value.ref3 : '',
                        conditionnement: (value.cond3) ? value.cond3 : '',
                        volume: (value.volume3) ? value.volume3 : '',
                        prix: (value.prixHT3) ? (value.prixHT3).toFixed(3) : ''
                    },
                    prixRef4: {
                        ref: (value.ref4) ? value.ref4 : '',
                        conditionnement: (value.cond4) ? value.cond4 : '',
                        volume: (value.volume4) ? value.volume4 : '',
                        prix: (value.prixHT4) ? (value.prixHT4).toFixed(3) : ''
                    },
                    prixRef5: {
                        ref: (value.ref5) ? value.ref5 : '',
                        conditionnement: (value.cond5) ? value.cond5 : '',
                        volume: (value.volume5) ? value.volume5 : '',
                        prix: (value.prixHT5) ? (value.prixHT5).toFixed(3) : ''
                    },
                    prixRef6: {
                        ref: (value.ref6) ? value.ref6 : '',
                        conditionnement: (value.cond6) ? value.cond6 : '',
                        volume: (value.volume6) ? value.volume6 : '',
                        prix: (value.prixHT6) ? (value.prixHT6).toFixed(3) : ''
                    }
                };
            };
            // Move on to the next object in store
            cursor.continue();

        } else {
            console.log("No more entries");
        }
    }

    return objvins;
}


// Open database or create it if not exists
openDb();

// Creation table index.html
setTimeout(function () {
    createCheckboxRegion(objVins);
    createCheckboxDomaine(objVins);
    createCheckboxCouleur(objVins);
    addLignesVins(objVins);

    $('#listevins .tablesorter').tablesorter({sortList: [[0, 0]], headers: {2: {sorter: 'digit'}}});

    $("#resultats_recherche").hide();
    $("#search").val("");
    search(objVins);
    showHideLines(objVins);
    createPopup(objVins);
    checkAll(objVins);
    // Creation objet vin pdf
    for (var i in  objVins) {
        var pdf = "https://lesliecordier.github.io/mtnr/" + objVins[i]['pdf'];
        console.log(urlPdf.indexOf(pdf));
        if(urlPdf.indexOf(pdf) === -1 && objVins[i]['pdf'] !== '#'){
            urlPdf.push(pdf);
        }
    }
    console.log(urlPdf);
}, 1000);

