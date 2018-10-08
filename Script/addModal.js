function reply_click(addViolation){
    // Get the modal
    var modal = document.getElementById('viewAddModal');

    // Get the button that opens the modal
    var btn = document.getElementById(addViolation);

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("viewAddClose")[0];

    // When the user clicks the button, open the modal 
    viewAddModal.style.display = "block";
    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        viewAddModal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            viewAddModal.style.display = "none";
        }
    }
}

function reply_click(addAdmin){
    // Get the modal
    var modal = document.getElementById('viewAddModal');

    // Get the button that opens the modal
    var btn = document.getElementById(addAdmin);

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("viewAddClose")[0];

    // When the user clicks the button, open the modal 
    viewAddModal.style.display = "block";
    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        viewAddModal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            viewAddModal.style.display = "none";
        }
    }
}