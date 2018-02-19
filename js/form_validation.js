// Wait for the DOM to be ready
$(function() {
  // Initialize form validation on the registration form.
  // It has the name attribute "registration"


  $("form[name='login_form']").validate({
    // Specify validation rules
    rules: {
      // The key name on the left side is the name attribute
      // of an input field. Validation rules are defined
      // on the right side
      login_emaili: "required",
      login_password: "required",
      login_email: {
        required: true,
        // Specify that email should be validated
        // by the built-in "email" rule
        email: true
      },
      login_password: {
        required: true
        // minlength: 6
      }
    },
    // Specify validation error messages
    messages: {
      // login_email: "กรุณากรอกอีเมล์",
      login_password: {
        required: "กรุณากรอกรหัสผ่าน"
        // minlength: "กรุณากรอกรหัสผ่านอย่างน้อย 6 ตัวอักษร"
      },
      login_email: {
      	required: "กรุณากรอกอีเมล์",
      	email: "กรุณากรอกอีเมล์ให้ถูกต้อง"

      }
    },
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
    submitHandler: function(form) {
      form.submit();
    }
  });


  $("form[id='form_register']").validate({
    rules: {
    	//refer accord to name element
      registerEmail: "required",
      registerPassword: "required",
      registerTel: "required",
      registerBirthdate: "required",

      registerEmail: {
        required: true,
        email: true
      },
      registerPassword: {
        required: true,
        minlength: 6
      },
      registerTel: {
      	required: true,
      	digits:true,
        minlength: 10,
        maxlength: 10
      },
      registerPerid: {
        digits:true,
        minlength: 13,
        maxlength: 13
      }
    },
    // Specify validation error messages
    messages: {
    	registerEmail: {
      		required: "กรุณากรอกอีเมล์",
      		email: "กรุณากรอกอีเมล์ให้ถูกต้อง"
      	},
      	registerPassword: {
        	required: "กรุณากรอกรหัสผ่าน",
        	minlength: "กรุณากรอกรหัสผ่านอย่างน้อย 6 ตัวอักษร"
      	},
        registerTel: {
          required: "กรุณากรอกเบอร์โทรศัพท์",
          minlength: "กรุณากรอกหมายเลขอย่างน้อย 10 ตัวอักษร",
          maxlength: "กรุณากรอกหมายเลขไม่เกิน 10 ตัวอักษร"
        },
        registerPerid: {
          minlength: "กรุณากรอกหมายเลขอย่างน้อย 13 ตัวอักษร",
          maxlength: "กรุณากรอกหมายเลขไม่เกิน 13 ตัวอักษร"
        },
        registerBirthdate: {
          required: "กรุณากรอกวันเดือนปีเกิด"
        }

    },
    submitHandler: function(form) {
      form.submit();
    }
  });

  $("form[id='form_forgetpass']").validate({
    rules: {
      //refer accord to name element
      input_forget_email: "required",

      input_forget_email: {
        required: true,
        email: true
      }
    },
    // Specify validation error messages
    messages: {
      input_forget_email: {
          required: "กรุณากรอกอีเมล์",
          email: "กรุณากรอกอีเมล์ให้ถูกต้อง"
        }

    },
    submitHandler: function(form) {
      form.submit();
    }
  });

  $("form[id='changepass_form']").validate({
    rules: {
      //refer accord to name element
      input_new_pass: "required",
      input_new_pass_confirm: "required",

      input_new_pass: {
        required: true,
        minlength: 6
      },
      input_new_pass_confirm: {
        required: true,
        minlength: 6,
        equalTo: "#input_new_pass"
      }
    },
    // Specify validation error messages
    messages: {
      // login_email: "กรุณากรอกอีเมล์",
      input_new_pass: {
        required: "กรุณากรอกรหัสผ่าน",
        minlength: "กรุณากรอกรหัสผ่านอย่างน้อย 6 ตัวอักษร",
        equalTo: "ท่านใส่รหัสผ่านไม่ตรงกัน"
      },
      input_new_pass_confirm: {
        required: "กรุณากรอกรหัสผ่าน",
        minlength: "กรุณากรอกรหัสผ่านอย่างน้อย 6 ตัวอักษร",
        equalTo: "ท่านใส่รหัสผ่านไม่ตรงกัน"
      }
    },
    submitHandler: function(form) {
      form.submit();
    }
  });



});
