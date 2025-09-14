// script.js

$(document).ready(function () {
    // Show/hide flight details section
    $(".viewDetailsBtn").click(function () {
      var flightId = $(this).closest(".flightRow").data("flight-id");
      var flightName = $(this).closest(".flightRow").find("td:eq(1)").text();
      var itinerary = $(this).closest(".flightRow").find("td:eq(2)").text();
      showFlightDetails(flightId, flightName, itinerary);
    });
  
    // Function to display flight details
    function showFlightDetails(flightId, flightName, itinerary) {
      // You can fetch additional flight details using AJAX or other methods
      var flightDetailsContent = `
        <h1>${flightName} Details</h1>
        <p>ID: ${flightId}</p>
        <p>Name: ${flightName}</p>
        <p>Itinerary: ${itinerary}</p>
        <p>Pending Passengers List: <!-- Add logic to fetch and display pending passengers --></p>
        <p>Registered Passengers List: <!-- Add logic to fetch and display registered passengers --></p>
        <button class="cancelFlightBtn">Cancel Flight</button>
      `;
  
      // Update flight details section content
      $("#flightDetails").html(flightDetailsContent);
  
      // Show the flight details section
      $("#flightDetails").removeClass("hidden");
    }
  
    // Function to hide flight details section
    function hideFlightDetails() {
      $("#flightDetails").addClass("hidden");
    }
  
    // Handle cancel flight button click
    $("#flightDetails").on("click", ".cancelFlightBtn", function () {
      // Implement cancel flight logic here
      // You may want to confirm the cancellation with the user and handle the refund process
      alert("Flight cancellation logic goes here");
      // Hide the flight details section after cancellation
      hideFlightDetails();
    });
  });
  