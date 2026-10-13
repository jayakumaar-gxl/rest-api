$(document).ready(function () {
  let employees = [];

  // Status Badge
  function getStatusLabel(status) {
    switch (Number(status)) {
      case 0:
        return '<span class="status-badge status-deleted">Deleted</span>';
      case 1:
        return '<span class="status-badge status-active">Active</span>';
      case 2:
        return '<span class="status-badge status-inactive">Inactive</span>';
      default:
        return '<span class="status-badge status-unknown">Unknown</span>';
    }
  }

  // Toast
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
      url: "./api/employees.php",
      type: "GET",
      dataType: "json",
      success: function (response) {

        employees=response.data || []; 
        gridApi.setGridOption('rowData',employees);
        // console.log("Employees loaded:", employees.length);       
      },
      error:function(xhr){
        console.error("Failed to load employees : ",xhr.responseText);        
      },
    });
  }

  // loadEmployees
  loadEmployees();

  // Ag Grid implementation for 
  let gridApi;
  const columnDefs = [
    {
      headerName: "ID",
      valueGetter: (params) => params.node.rowIndex + 1,
      maxWidth: 60,
      sortable: true,
    },
    {
      field: "name",
      headerName: "Name",
      filter: true,
      sortable: true,
    },
    {
      field: "email",
      headerName: "Email",
      filter: true,
      sortable: true,
    },
    {
      field: "department",
      headerName: "Department",
      filter: true,
      sortable: true,
    },
    {
      field: "status",
      headerName: "Status",
      sortable: true,
      filter: "agTextColumnFilter",

      filterValueGetter: (params) => {
        const status = Number(params.data.status);

        if (status === 1) return "Active";
        if (status === 2) return "Inactive";
        if (status === 0) return "Deleted";

        return "Unknown";
      },

      cellRenderer: (params) => {
        const status = Number(params.value);
        const badge = document.createElement("span");

        badge.classList.add("status-badge");

        if (status === 1) {
          badge.classList.add("status-active");
          badge.textContent = "Active";
        } else if (status === 2) {
          badge.classList.add("status-inactive");
          badge.textContent = "Inactive";
        } else if (status === 0) {
          badge.classList.add("status-deleted");
          badge.textContent = "Deleted";
        } else {
          badge.classList.add("status-unknown");
          badge.textContent = "Unknown";
        }

        return badge;
      },
    },
    {
      headerName: "Actions",
      sortable: false,
      filter: false,
      cellRenderer: (params) => {
        const wrapper = document.createElement("div");

        wrapper.innerHTML = `
            <button type="button"
                    class="editEmployeeBtn"
                    data-id="${params.data.id}">
                <i class="fa-regular fa-pen-to-square"></i>
            </button>

            <button type="button"
                    class="deleteEmployeeBtn"
                    data-id="${params.data.id}">
                <i class="fa-solid fa-trash"></i>
            </button>
        `;

        return wrapper;
      },
    },
  ];
  const gridOptions={
    columnDefs:columnDefs,
    rowData:[],
    rowHeight:40,
    headerHeight:50,
    defaultColDef:{
      flex:1,
      minWidth:130,
      resizable:true,
    },
    pagination:true,
    paginationPageSize:10,
    paginationPageSizeSelector:[10,20,50,100,200],
    domLayout: "autoHeight",
  };
  gridApi=agGrid.createGrid(
    document.querySelector('#employeeGrid'),
    gridOptions
  );


  // Create Employee
  $("#addEmployeeBtn").on("click", function () {
    // $('#employeeFormSection').prop('hidden',false);
    $("#employeeFormSection").prop("hidden", false).hide().fadeIn(700);
    $("#formTitle").text("Add Employee");
    $("#saveEmployeeBtn").prop("hidden", false);
    $("#updateEmployeeBtn").prop("hidden", true);
    $("#statusField").hide();
  });

  // Cancel the Add Employee Flow
  let employeeForm=document.getElementById('employeeForm');
  $("#cancelBtn").on("click", function () {
    // $('#employeeFormSection').prop('hidden',true);
    let confirmed = confirm("Are You Sure Want to Cancel the Process ?");
    if (confirmed) {
      employeeForm.reset();
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
          showToast(response.message || "Employee added successfully !","success",);
        } else {
          //   $("#toast").text(response.message);
          showToast(response.message || "Employee Insertion Failed !", "error");
        }
      },
      error: function (xhr) {
        console.log("POST Error : ", xhr.responseText);
        let response = xhr.responseJSON;
        if (response && response.message) {
          showToast(response.message, "error");
        } else {
          showToast("Something went wrong. Please try again.", "error");
        }
      },
    });
  });

  // Update Employee (PUT)
  // $('.editEmployeeBtn').on('click',function(){
  $(document).on("click", ".editEmployeeBtn", function () {
    // alert('working')
    $("#statusField").show();
    let employeeId = $(this).data("id");
    console.log("Employee ID : ",employeeId);

    let employee = employees.find(function (item) {
      return item.id == employeeId;
    });
    // console.log(employee);

    if (!employee) {
      showToast("Employee not found", "error");
      return;
    }

    $("#employeeId").val(employee.id);
    $("#name").val(employee.name);
    $("#email").val(employee.email);
    $("#department").val(employee.department);
    $("#status").val(employee.status);

    $("#formTitle").text("Edit Employee");
    $("#saveEmployeeBtn").prop("hidden", true);
    $("#updateEmployeeBtn").prop("hidden", false);

    $("#employeeFormSection").prop("hidden", false).hide().fadeIn(400);
  });
  $("#updateEmployeeBtn").on("click", function () {
    
    let employeeId = $("#employeeId").val();

    let employeeData = {
      name: $("#name").val().trim(),
      email: $("#email").val().trim(),
      department: $("#department").val().trim(),
      status:$('#status').val().trim(),
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
        let response = xhr.responseJSON;
        if (response && response.message) {
          showToast(response.message, "error");
        } else {
          showToast("Something went wrong. Please try again.", "error");
        }
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
