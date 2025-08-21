/**
 * dropdown.js
 *
 * This script provides the functionality for the login dropdown menu
 * in the website header. It toggles the visibility of the dropdown content
 * and closes it when the user clicks outside.
 */

/**
 * Toggles the visibility of the dropdown content.
 */
function toggleDropdown() {
    // Get the dropdown content element by its ID
    var dropdown = document.getElementById("dropdown");
    // Toggle the 'show' class to display or hide the dropdown
    dropdown.classList.toggle("show");
}

/**
 * Closes the dropdown if the user clicks outside of it.
 * This function is attached to the global window.onclick event.
 */
window.onclick = function(event) {
    // Check if the clicked element is NOT the dropdown button
    if (!event.target.matches('.dropbtn')) {
        // Get all elements with the class "dropdown-content"
        var dropdowns = document.getElementsByClassName("dropdown-content");
        var i;
        // Loop through all dropdowns
        for (i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            // If a dropdown is currently visible (has the 'show' class)
            if (openDropdown.classList.contains('show')) {
                // Remove the 'show' class to hide it
                openDropdown.classList.remove('show');
            }
        }
    }
}
