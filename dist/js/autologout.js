document.addEventListener('DOMContentLoaded', (event) => {
    console.log("DOM fully loaded and parsed");

    // Set up variables
    var inactiveTime = 0; // Time since last activity
    var maxInactiveTime = 30; // Maximum inactive time before logout (in seconds)

    // Function to reset the inactive time
    function resetInactiveTime() {
        inactiveTime = 0;
        console.log("Inactive time reset"); // Debugging log
    }

    // Function to handle user activity
    function handleActivity() {
        resetInactiveTime(); // Reset inactive time on activity
    }

    // Event listeners for different types of user activity
    document.addEventListener("mousemove", handleActivity);
    document.addEventListener("mousedown", handleActivity);
    document.addEventListener("keydown", handleActivity);
    document.addEventListener("touchstart", handleActivity); // For mobile devices
    document.addEventListener("scroll", handleActivity); // Capture scroll events

    // Function to check for inactivity and logout if necessary
    function checkInactivity() {
        inactiveTime++;
        console.log("Inactive time: " + inactiveTime + " seconds"); // Debugging log
        if (inactiveTime >= maxInactiveTime) {
            console.log("User has been inactive for " + maxInactiveTime + " seconds. Logging out..."); // Debugging log
            // Redirect to logout page
            window.location.href = 'logout.php';
        }
    }

    // Set up interval to check for inactivity every second
    setInterval(checkInactivity, 1000);

    // Confirm script is running
    console.log("Auto logout script loaded");
});