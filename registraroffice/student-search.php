<?php 
session_start();
if (isset($_SESSION['r_user_id']) && 
    isset($_SESSION['role'])) {

    if ($_SESSION['role'] == 'Registrar Office') {
       if (isset($_GET['searchKey'])) {

       $search_key = $_GET['searchKey'];
       include "../db_connection.php";
       include "data/student.php";
       include "data/semester.php";
       include "data/section.php";
       $students = searchStudents($search_key, $conn);
       $sections = getAllSections($conn);
       $grades = getAllGrades($conn); 
 ?>
<?php
       include "../header.php";
?>
<body>
    <?php 
        include "../nav.php";
        if ($sections == 0 || $grades == 0) { ?>
           
          <div class="alert alert-info" role="alert">
           First create section and class
          </div>
           <a href="class.php"
           class="btn btn-dark">Go Back</a>
      <?php } ?>
    <?php 
        if ($students != 0) {
     ?>
     <div class="container mt-5">
        <a href="student-add.php"
           class="btn btn-dark">Add New Student</a>
        <a href="student.php"
           class="btn btn-dark">Go Back</a>
           
           <form action="student-search.php" 
                 class="mt-3 n-table"
                 method="post">
             <div class="input-group mb-3">
                <input type="text" 
                       class="form-control"
                       name="searchKey"
                       placeholder="Search...">
                <button class="btn btn-primary">
                        <i class="fa fa-search" 
                           aria-hidden="true"></i>
                      </button>
             </div>
           </form>

           <?php if (isset($_GET['error'])) { ?>
            <div class="alert alert-danger mt-3 n-table" 
                 role="alert">
              <?=$_GET['error']?>
            </div>
            <?php } ?>

          <?php if (isset($_GET['success'])) { ?>
            <div class="alert alert-info mt-3 n-table" 
                 role="alert">
              <?=$_GET['success']?>
            </div>
            <?php } ?>

           <div class="table-responsive">
              <table class="table table-bordered mt-3 n-table">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">ID</th>
                    <th scope="col">First Name</th>
                    <th scope="col">Last Name</th>
                    <th scope="col">Username</th>
                    <th scope="col">Semester</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $i = 0; foreach ($students as $student ) { 
                    $i++;  ?>
                  <tr>
                    <th scope="row"><?=$i?></th>
                    <td><?=$student['student_id']?></td>
                    <td>
                      <a href="student-view.php?student_id=<?=$student['student_id']?>">
                        <?=$student['fname']?>
                      </a>
                    </td>
                    <td><?=$student['lname']?></td>
                    <td><?=$student['username']?></td>
                    <td>
                      <?php 
                           $grade = $student['semester'];
                           $g_temp = getGradeById($grade, $conn);
                           if ($g_temp != 0) {
                              echo $g_temp['semester_code'].'-'.
                                     $g_temp['semester'];
                            }
                        ?>
                    </td>
                    <td>
                        <a href="student-edit.php?student_id=<?=$student['student_id']?>"
                           class="btn btn-warning">Edit</a>
                        <a href="student-delete.php?student_id=<?=$student['student_id']?>"
                           class="btn btn-danger">Delete</a>
                    </td>
                  </tr>
                <?php } ?>
                </tbody>
              </table>
           </div>
         <?php }else{ ?>
             <div class="alert alert-info .w-450 m-5" 
                  role="alert">
                    No Results Found
                 <a href="student.php"
                   class="btn btn-dark">Go Back</a>
              </div>
         <?php } ?>
     </div>
     	

</body>
</html>
<?php 
          include "../footer.php";
?>
<?php 
    }else {
      header("Location: student.php");
      exit;
    } 

  }else {
    header("Location: ../login.php");
    exit;
  } 
}else {
	header("Location: ../login.php");
	exit;
} 

?>