//  Declare SQL Query for SQLite
 
 
var selectAllStatement = "SELECT * FROM Contacts";
 
var db = openDatabase("montaner", "1.0", "Catalogue vins Montaner", 1024*1024);  // Open SQLite Database
 
var dataset;
 
var DataType;
 
 function initDatabase()  // Function Call When Page is ready.
 
{
 
    try {
 
        if (!window.openDatabase)  // Check browser is supported SQLite or not.
 
        {
 
            alert('Databases are not supported in this browser.');
 
        }
 
        else {
            var createStatement = "CREATE TABLE IF NOT EXISTS Contacts (id INTEGER PRIMARY KEY AUTOINCREMENT, username TEXT, useremail TEXT)";
            var insertStatement = "INSERT INTO Contacts (username, useremail) VALUES (?, ?)";
            db.transaction(function (tx) { tx.executeSql(createStatement, []); });
            db.transaction(function (tx) { tx.executeSql(insertStatement, ['user', 'email'], showRecords); });
 
            alert('Database opened');
 
        }
 
    }
 
    catch (e) {
 
        if (e === 2) {
 
            // Version number mismatch. 
 
            console.log("Invalid database version.");
 
        } else {
 
            console.log("Unknown error " + e + ".");
 
        }
 
        return;
 
    }
 
}
 
 
 
function showRecords() // Function For Retrive data from Database Display records as list
 
{
 
    $("#results").html('');
 
    db.transaction(function (tx) {
 
        tx.executeSql(selectAllStatement, [], function (tx, result) {
 
            dataset = result.rows;
 
            for (var i = 0, item = null; i < dataset.length; i++) {
                
                item = dataset.item(i);
                alert(dataset.item(i)['username']);
                alert(dataset.length);
 
            }
 
        });
 
    });
 
}
 
$(document).ready(function () // Call function when page is ready for load..
 
{
    initDatabase();
});
 
  