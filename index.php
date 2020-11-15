<!DOCTYPE html>
<html>
    <head>
        <title> Lord of the Rings Database Lookup </title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.css">
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Tangerine&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Charm&display=swap" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    </head>
    
    <body>
        <br>
        <h1> One Search to Rule Them All </h1>
        <div id="flourish"><img src="img/flourish1.png" alt="LOTR flourish"></div>
        <br>
        <div id="lookupForm">
            <div id="fields">
                <input type="text" name="input" id="input" placeholder="Search for a Lord of the Rings character or quote" class="form-control">
            </div>
            <div id="button"><input type="submit" id="search" value="Search" class="btn btn-outline-warning"></div>
            <div id="flourish"><img src="img/flourish.png" alt="LOTR flourish"></div>
            <div id="resultsCharTitle"></div>
            <div id="resultsChar"> </div>
            <div id="resultsQuoteTitle"></div>
            <div id="resultsQuote"></div>
            <br>
        </div>
        
        <footer>
            <br>
            <hr>
            CST336 Internet Programming - &copy;2020 Josh Hansen
            <br><br>
        </footer>
        
        <script>
        $(document).ready(function() {
            /*global $*/
            /*global fetch*/
            
            
            $(document).on('keypress',function(e) {
                if(e.which == 13) {
                    $("#search").click();
                }
            });
                        
        
            $("#search").on("click", async function() {
                
                if ($("#input").val() == "") {
                    $("#resultsChar").css("color", "red");
                    $("#resultsChar").html("<br> Search is empty.");
                    return;
                }
                
                let foundCharResult = false;
                let foundQuoteResult = false;
                let searchVal = $("#input").val().toLowerCase();
                
                $("#resultsCharTitle").html("");
                $("#resultsChar").html("");
                $("#resultsChar").css("color", "white");
                $("#resultsQuoteTitle").html("");
                $("#resultsQuote").html("");
                
                //Character search
                let charURL = "https://the-one-api.dev/v2/character";
                let charResponse = await fetch(charURL, { headers: { 'Authorization': 'Bearer j9fmne_OCUFrewSmJwxu' }});
                let charData = await charResponse.json();
                
                charData.docs.forEach( async function(i){ 
                    
                    //if the string is present in any string of any field, list the entry
                    if ((i.birth.toLowerCase().search(searchVal) > -1) ||
                        (i.death.toLowerCase().search(searchVal) > -1) ||
                        (i.hair.toLowerCase().search(searchVal) > -1) ||
                        (i.height.toLowerCase().search(searchVal) > -1) ||
                        (i.name.toLowerCase().search(searchVal) > -1) ||
                        (i.race.toLowerCase().search(searchVal) > -1) ||
                        (i.realm.toLowerCase().search(searchVal) > -1) ||
                        (i.spouse.toLowerCase().search(searchVal) > -1))
                    {
                        if (!foundCharResult) {
                            $("#resultsCharTitle").append("<br><br>CHARACTERS<br><br>");
                        }
                        
                        foundCharResult = true;
                        
                        //Get image for character
                        let foundImage = false;
                        let url = `https://www.googleapis.com/customsearch/v1?key=AIzaSyA7veWGrtxWmXyC-SPizusMcgee6a1xGpI&cx=8c4d3b0d23963e3df&q=${i.name}`;
                        let resp = await fetch(url);
                        let data = await resp.json();
                        let finalURL = "";
                        // reduce the result a bit for simplification
                        
                        try {
                            if (data.items[0].pagemap.cse_image[0].src.search(".jpg") > -1) {
                                finalURL = data.items[0].pagemap.cse_image[0].src.split(".jpg",1) + ".jpg";
                                foundImage = true;
                            }
                            else if (data.items[0].pagemap.cse_image[0].src.search(".jpeg") > -1) {
                                finalURL = data.items[0].pagemap.cse_image[0].src.split(".jpeg",1) + ".jpeg";
                                foundImage = true;
                            }
                            else if (data.items[0].pagemap.cse_image[0].src.search(".png") > -1) {
                                finalURL = data.items[0].pagemap.cse_image[0].src.split(".png",1) + ".png";
                                foundImage = true;
                            }
                            
                        }
                        catch (err) {
                            console.log(err);
                        }
                        
                        if (foundImage) { 
                            $("#resultsChar").append(`<div id="image"><img id="characterImg" src="${finalURL}" height=200px></div>`);
                        }
                        
                        //list all fields of entry that have data
                        $("#resultsChar").append(` Name: ${i.name}`);
                        if (i.birth != "")
                            $("#resultsChar").append(`<br> Birth: ${i.birth}`);
                        if (i.death != "")
                            $("#resultsChar").append(`<br> Death: ${i.death}`);
                        if (i.gender != "")
                            $("#resultsChar").append(`<br> Gender: ${i.gender}`);
                        if (i.hair != "")
                            $("#resultsChar").append(`<br> Hair: ${i.hair}`);
                        if (i.height != "")
                            $("#resultsChar").append(`<br> Height: ${i.height}`);
                        if (i.race != "")
                            $("#resultsChar").append(`<br> Race: ${i.race}`);
                        if (i.realm != "")
                            $("#resultsChar").append(`<br> Realm: ${i.realm}`);
                        if (i.spouse != "")
                            $("#resultsChar").append(`<br> Spouse: ${i.spouse}`);
                        $("#resultsChar").append("<br><br>");
                    }
                });
                
                //Quote search
                let quoteURL = "https://the-one-api.dev/v2/quote";
                let quoteResponse = await fetch(quoteURL, { headers: { 'Authorization': 'Bearer j9fmne_OCUFrewSmJwxu' }})
                let quoteData = await quoteResponse.json();
                
                //$("#results").append(quoteData);
                
                quoteData.docs.forEach( async function(i){ 
                    
                    if (i.dialog.toLowerCase().search(searchVal) > -1) {
                        if (!foundQuoteResult) {
                            $("#resultsQuoteTitle").append("<br><br>QUOTES");
                        }
                        
                        let characterName = ""
                        foundQuoteResult = true;
                        
                        charData.docs.forEach( async function(j){ 
                            if (i.character == j._id)
                            characterName = "&nbsp&nbsp - " + j.name;
                        });
                        
                        $("#resultsQuote").append(`<br><br>"${i.dialog}" <br> ${characterName}`);
                    }
                });
                 
                 
                if (!foundCharResult && !foundQuoteResult) {
                    $("#resultsQuote").append("<br>No results found.");
                }
            });
        });
        </script>
    </body>
</html>