$(document).ready(function(){
    console.log('JS working');    

    $.ajax({
        url:'./api/employees.php?page=1&limit=10',
        type:"GET",
        dataType:"json",
        success:function(response){
            // console.log("API response",response.data);   
            $('#employeeTableBody').html("");         
            $.each(response.data,function(index,employee){
                // console.log(index,employee.id,employee.name);  
                let row=`
                    <tr>
                        <td>${index}</td>
                        <td>${employee.name}</td>
                        <td>${employee.email}</td>
                        <td>${employee.department}</td>
                        <td>Actions</td>
                    </tr>        
                `;   
                $('#employeeTableBody').append(row);          
            })
        }
    });

})