<?php
// include_once('./config/conn.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <div class="container">
        
        <header class="page-header">
            <div>
                <h1>Employee Management</h1>
                <p>Manage Employee records using REST API</p>
            </div>
        </header>

        <section class="form-section" id="employeeFormSection" hidden>
            <h2 id="formTitle">Add Employee</h2>

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" placeholder="Enter your name" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Enter your Email" required>
            </div>

            <div class="form-group">
                <label for="department">Department</label>
                <input type="text" name="department" id="department" placeholder="Enter your Department" required>
            </div>

            <button type="reset">Clear</button>
            <button type="submit" id="saveEmployeeBtn">Save Employee</button>
            <button type="button" id="cancelBtn">Cancel</button>

        </section>

        <section class="table-section">
            <h2>Employee List</h2>
            <p id="message"></p>
            <div class="table-wrapper">
                <table>

                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody id="employeeTableBody">
                        <tr>
                            <td colspan="5">Loading Employees... </td>
                        </tr>
                    </tbody>

                </table>
            </div>
        </section>

    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/4.0.0/jquery.min.js" integrity="sha512-8LENNbXmzI/Gbj+OwXmqR6V4QaUAw0/porPzy1+dQoJqC0JPHedWoe0DDOTL2uHA5XXJyIsPtiMHH86pVlay6A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="./js/app.js"></script>
</body>

</html>