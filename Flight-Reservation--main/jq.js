$(document).ready(function() {
    // Add datepicker to the form (using jQuery UI)
    $("#registrationForm input[name='dob']").datepicker();
  
    // Validate password match
    $("#registrationForm").submit(function(e) {
      var password = $("#password").val();
      var confirmPassword = $("#confirmPassword").val();
  
      if (password !== confirmPassword) {
        alert("Passwords do not match");
        e.preventDefault();
      }
    });
  });
  