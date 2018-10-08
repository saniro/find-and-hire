function ChangeTab(elementID){  
    if (elementID == "propertyCondominium"){   
        displayElements("floorType");
        hideElements("floorArea");
    }
    else if (elementID == "propertyApartment"){   
        displayElements("floorType");
        hideElements("floorArea");
    }
    else if (elementID == "propertyHouse"){
        displayElements("floorArea");
        hideElements("floorType");

    }
}
function displayElements(className){
    var elements = document.getElementsByClassName(className);
    for(var x=0; x<elements.length;x++){
        elements[x].style.display = "block";
    }
}
function hideElements(className1,className2,className3){       
    var elementsID1 = document.getElementsByClassName(className1);
    var elementsID2 = document.getElementsByClassName(className2);
    var elementsID3 = document.getElementsByClassName(className3);
    for(var x=0; x<elementsID1.length; x++){
        elementsID1[x].style.display = "none";
    }
    for(var y=0; y<elementsID2.length; y++){
        elementsID2[y].style.display = "none";
    }
    for(var y=0; y<elementsID3.length; y++){
        elementsID3[y].style.display = "none";
    }
}