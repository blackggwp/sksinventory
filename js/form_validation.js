// Wait for the DOM to be ready
$(function() {
  // Initialize form validation on the registration form.
  // It has the name attribute "registration"

  $("form[name='form_login']").validate({
    // It will enable hidden field validation.
    // So You will get validation for Select 2
    ignore: [],
    // ignore: 'input[type=hidden]',
    // Specify validation rules
    rules: {
      // The key name on the left side is the name attribute
      // of an input field. Validation rules are defined
      // on the right side
      selectPlant: "required",
      empcode: "required",
      empcode: {
        required: true,
        number: true,
        minlength: 6,
        maxlength: 6
      }
    },
    // Specify validation error messages
    messages: {
      selectPlant: {
        required: "กรุณาเลือกสาขา"
      },
      empcode: {
        required: "กรุณาระบุรหัสพนักงาน",
        number: "กรุณาระบุเป็นตัวเลข",
        minlength: "กรุณากรอกรหัสอย่างน้อย 6 ตัวอักษร",
        maxlength: "กรุณากรอกรหัสไม่เกิน 6 ตัวอักษร"
      }
    },
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
    submitHandler: function(form) {
      form.submit();
    }
  });
  
  $("form[name='form_data']").validate({
    rules: {
      mat_qty: {
        number: true,
        require_from_group: [1, ".mat_qty"]
      }
    },
    submitHandler: function(form) {
      form.submit();
    }
  });
});
