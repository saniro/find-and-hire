function reply_click(clicked_id)
{
    // Get the modal
var modal = document.getElementById('viewModal');

// Get the button that opens the modal
var btn = document.getElementById(clicked_id);

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("viewClose")[0];

// When the user clicks the button, open the modal 
viewModal.style.display = "block";
// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    viewModal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        viewModal.style.display = "none";
    }
}
}