(function ($) {
  $(document).ready(function () {
    // Country code drop down.
    // var countryData = window.intlTelInputGlobals.getCountryData();

    // var $dialCodeList = $(".country_dropdown_list");

    // Iterate through the countryData array
    // for (var i = 0; i < countryData.length; i++) {
    //   var dialCode = countryData[i].dialCode;

    //   $dialCodeList.append(
    //     "<li data-value=" +
    //       "+" +
    //       dialCode +
    //       " class='select-dropdown__list-item'>" +
    //       "+" +
    //       dialCode +
    //       "</li>"
    //   );
    // }

    // Validate and submit an inquiry of contact form.
    $(".submit-inquiry").on("click", function (e) {
      e.preventDefault();
      // Clear any previous error messages
      $(".error").text("");
      $(".form-group").removeClass("form-error");
      $(".phone-number-right").removeClass("form-error");
      $(".phone-number-left").removeClass("form-error");
      $(".success-response").text("");

      // Validate Name
      let name = $(".inquiry-name").val();
      if (name === "") {
        $(".inquiry-name").closest(".form-group").addClass("form-error");
      } else if (name.length < 2) {
        $(".inquiry-name").closest(".form-group").addClass("form-error");
      }

      // Validate Email
      let email = $(".inquiry-email").val();
      let emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
      if (email === "") {
        $(".inquiry-email").closest(".form-group").addClass("form-error");
      } else if (!emailPattern.test(email)) {
        $(".inquiry-email").closest(".form-group").addClass("form-error");
      }

      // Validate Phone number
      let phone_number = $(".inquiry-phone").val();
      if (phone_number === "") {
        $(".inquiry-phone").parent().addClass("form-error");
      }

      // Validate country code.
      let phone_country_code = $(".inquiry-country").attr("data-value");
      if (phone_country_code === "") {
        $(".inquiry-country").parent().addClass("form-error");
      }

      // Validate Reason
      let reason = $(".inquiry-reason").attr("data-value");
      if (reason == 0) {
        $(".inquiry-reason").closest(".form-group").addClass("form-error");
      }

      // Validate Message
      let message = $(".inquiry-message").val();
      if (message === "") {
        $(".inquiry-message").closest(".form-group").addClass("form-error");
      } else if (message.length < 2) {
        $(".inquiry-message").closest(".form-group").addClass("form-error");
      }

      // Check if any errors exist
      let errorsExist = $(".form-group").hasClass("form-error");

      // If there are errors, do not submit the form
      if (errorsExist) {
        $("#submit-error").text(
          "One or more fields have an error. Please check and try again."
        );
        return;
      } else {
        $(".contact-form-loader").show();
        $(".form-group").removeClass("form-error");
        $(".phone-number-left").removeClass("form-error");
        $(".phone-number-right").removeClass("form-error");
        $.ajax({
          url: contactScript.ajax_url,
          type: "POST",
          data: {
            action: "akd_submit_inquiry_details",
            name: name,
            email: email,
            phone_country_code: phone_country_code,
            phone_number: phone_number,
            reason: reason,
            message: message,
            nonce: contactScript.nonce,
          },
          success: function (response) {
            $(".contact-form-loader").hide();
            $(".form-group").removeClass("form-error");
            if (response.success === true) {
              $(".success-response").html(
                "Thank you for your message. It has been sent."
              );
              $(".inquiry-form")[0].reset();
              $(".inquiry-country").val(country_code);
            } else if (response.success === false) {
              let errorObj = response.data.errors;

              // Display the error messages for each field.
              if (errorObj.name) {
                $("#name-error").closest(".form-group").addClass("form-error");
              }
              if (errorObj.email) {
                $("#email-error").closest(".form-group").addClass("form-error");
              }
              if (errorObj.phone_number) {
                $("#phone-error").closest(".form-group").addClass("form-error");
              }
              if (errorObj.reason) {
                $("#reason-error")
                  .closest(".form-group")
                  .addClass("form-error");
              }
              if (errorObj.message) {
                $("#message-error")
                  .closest(".form-group")
                  .addClass("form-error");
              }
            }
          },
          complete: function () {
            $(".contact-form-loader").hide();
          },
        });
      }
    });
  });
})(jQuery);
