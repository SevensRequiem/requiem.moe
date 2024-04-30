// Get all span elements
const spans = document.getElementsByTagName('span');

// Initialize a counter for hue
let hue = 0;

// Function to update color
function updateColor() {
    // Iterate over each span
    for (let i = 0; i < spans.length; i++) {
        // Check if the span has the class 'rainbow'
        if (spans[i].classList.contains('rainbow')) {
            // Change the color of the text to a color in the spectrum
            spans[i].style.color = `hsl(${hue}, 100%, 50%)`;
        }
        // Check if the span has the class 'glow'
        else if (spans[i].classList.contains('rainglow')) {
            // Add a glow effect to the text
            spans[i].style.textShadow = `0 0 10px hsl(${hue}, 100%, 50%), 0 0 20px hsl(${hue}, 100%, 50%), 0 0 30px hsl(${hue}, 100%, 50%), 0 0 40px hsl(${hue}, 100%, 50%)`;
        }
    }

    // Increment the hue
    hue = (hue + 1) % 360;
}

// Call updateColor every 10 milliseconds
setInterval(updateColor, 10);