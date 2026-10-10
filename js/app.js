$(document).ready(function () {
  let employees=[];

  // TOAST
  let toastTimer;

  function showToast(message, type = "success") {
    clearTimeout(toastTimer);
    $("#toast")
      .stop(true, true)
      .removeClass("success error show")
      .addClass(type)
      .empty();
    $("<span>").text(message).appendTo("#toast");

    $("<button>")
      .attr({
        type: "button",
        class: "toast-close",
        "aria-label": "Close notification",
      })
      .html('<i class="fa-solid fa-xmark" aria-hidden="true"></i>')
      .appendTo("#toast")
      .on("click", function () {
        clearTimeout(toastTimer);
        $("#toast").removeClass("show");
      });

    $("#toast").addClass("show");

    toastTimer = setTimeout(function () {
      $("#toast").removeClass("show");
    }, 5000);
  }

  // Show Employee (GET)
  function loadEmployees() {
    $.ajax({
      url: "./api/employees.php?page=1&limit=20",
      type: "GET",
      dataType: "json",
      success: function (response) {
        // console.log("API response",response.data);
        //   $("#employeeTableBody").html("");
        employees=response.data;

        $("#employeeTableBody").empty();

        $.each(response.data, function (index, employee) {
          // console.log(index,employee.id,employee.name);
          let row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${employee.name}</td>
                        <td>${employee.email}</td>
                        <td>${employee.department}</td>
                        <td>
                          <button type="button" class="editEmployeeBtn" data-id="${employee.id}">
                            <i class="fa-regular fa-pen-to-square"></i>
                          </button>
                          <button type="button" class="deleteEmployeeBtn" data-id="${employee.id}">
                            <i class="fa-solid fa-trash"></i>
                          </button>
                        </td>
                    </tr>        
                `;
          $("#employeeTableBody").append(row);
        });
      },
    });
  }

  // loadEmployees
  loadEmployees();

  // Create Employee
  $("#addEmployeeBtn").on("click", function () {
    // $('#employeeFormSection').prop('hidden',false);
    $("#employeeFormSection").prop("hidden", false).hide().fadeIn(700);
    $("#formTitle").text("Add Employee"); 
    $("#saveEmployeeBtn").prop('hidden',false);
    $("#updateEmployeeBtn").prop('hidden',true);
  });

  // Cancel the Add Employee Flow
  $("#cancelBtn").on("click", function () {
    // $('#employeeFormSection').prop('hidden',true);
    let confirmed = confirm("Are You Sure Want to Cancel the Process ?");
    if (confirmed) {
      $("#employeeFormSection").fadeOut(400, function () {
        $(this).prop("hidden", true);
      });
    }
  });

  // (POST)
  $("#employeeForm").on("submit", function (event) {
    // alert('Form Submit working');
    event.preventDefault();
    console.log("Employee Form Submitted");

    let name = $("#name").val();
    let email = $("#email").val();
    let department = $("#department").val();

    // console.log(name);
    // console.log(email);
    // console.log(department);

    let employeeData = {
      name: name,
      email: email,
      department: department,
    };

    // console.log("Employee Data : ", employeeData);

    $.ajax({
      url: "./api/employees.php",
      type: "POST",
      contentType: "application/json",
      dataType: "json",
      data: JSON.stringify(employeeData),
      success: function (response) {
        console.log("POST Response : ", response);
        if (response.status) {
          // Reset the form
          $("#employeeForm")[0].reset();

          //Close the form
          $("#employeeFormSection").fadeOut(400, function () {
            $(this).prop("hidden", true);
          });

          // Load Employees table
          loadEmployees();

          // Response message
          //   $("#toast").text(response.message);
          showToast(
            response.message || "Employee added successfully !",
            "success",
          );
        } else {
          //   $("#toast").text(response.message);
          showToast(response.message || "Employee Insertion Failed !", "error");
        }
      },
      error: function (xhr) {
        console.log("POST Error : ", xhr.responseText);
        showToast("Something went wrong. Please try again.", "error");
      },
    });
  });

  // Update Employee (PUT)
  // $('.editEmployeeBtn').on('click',function(){
  $(document).on('click','.editEmployeeBtn',function(){
    // console.log("Employee ID : ",employeeId);
    // alert('working')
    let employeeId=$(this).data('id');

    let employee=employees.find(function(item){
      return item.id==employeeId;
    })
    // console.log(employee);

    if(!employee){
      showToast('Employee not found','error');
      return;
    }

    $('#employeeId').val(employee.id);
    $('#name').val(employee.name);
    $('#email').val(employee.email);
    $('#department').val(employee.department);

    $('#formTitle').text('Edit Employee');
    $("#saveEmployeeBtn").prop('hidden',true);
    $("#updateEmployeeBtn").prop('hidden',false);

    $('#employeeFormSection').prop('hidden',false).
    hide().fadeIn(400);
    
  });
  $("#updateEmployeeBtn").on("click", function () {
    let employeeId = $("#employeeId").val();

    let employeeData = {
      name: $("#name").val().trim(),
      email: $("#email").val().trim(),
      department: $("#department").val().trim(),
    };

    // console.log("Updating Employee ID:", employeeId);
    // console.log("Updated Data:", employeeData);

    $.ajax({
      url: "./api/employees.php?id=" + employeeId,
      type: "PUT",
      contentType: "application/json",
      dataType: "JSON",
      data: JSON.stringify(employeeData),
      success: function (response) {
        //console.log(response);
        if (response.status) {
          // Reset the form
          $("#employeeForm")[0].reset();

          //Close the form
          $("#employeeFormSection").fadeOut(400, function () {
            $(this).prop("hidden", true);
          });

          // Load Employees table
          loadEmployees();

          // Response message
          showToast(
            response.message || "Employee details updated successfully !",
            "success",
          );
        } else {
          //   $("#toast").text(response.message);
          showToast(response.message || "Employee update Failed !", "error");
        }
      },
      error: function (xhr) {
        console.log("Error : ", xhr.responseText);
      },
    });
  });


  // Delete Employee
  $(document).on("click", ".deleteEmployeeBtn", function () {
    let employeeId = $(this).data("id");
    //console.log("Delete Employee ID:", employeeId);

    let confirmed = confirm("Are you sure you want to delete this employee?");

    if (confirmed) {
      $.ajax({
        url: "./api/employees.php?id=" + employeeId,
        type: "DELETE",
        dataType: "json",

        success: function (response) {
          console.log("DELETE Response:", response);

          if (response.status) {
            loadEmployees();
            showToast("Employee deleted successfully", "success");
          } else {
            showToast(response.message || "Unable to delete employee", "error");
          }
        },

        error: function (xhr) {
          console.log("DELETE Error:", xhr.responseText);
        },
      });
    }
  });

});
