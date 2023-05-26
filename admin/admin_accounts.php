<?php
include '../components/connect.php';
session_start();
$admin_id = $_SESSION['admin_id'];
if(!isset($admin_id)){
   header('location:admin_login.php');
}
if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_admins = $conn->prepare("DELETE FROM `admins` WHERE id = ?");
   $delete_admins->execute([$delete_id]);
   header('location:admin_accounts.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>ProTools - Admin Accounts</title>
   <link rel="stylesheet" href="../css/admin_style.css">
   <link rel="stylesheet" href="../css/admin-acc-style.css">
   <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css">
</head>
<body>
<?php include '../components/admin_header.php'; ?>
<section class="admin-accounts">
   <h1 class="heading">ADMIN ACCOUNTS</h1>
   <div class="box-container">
      <div class="table-responsive">
         <table id="adminTable" class="table">
            <thead>
               <tr>
                  <th>Admin ID</th>
                  <th>Admin Name</th>
                  <th>Actions</th>
               </tr>
            </thead>
            <tbody>
               <?php
               $select_accounts = $conn->prepare("SELECT * FROM `admins`");
               $select_accounts->execute();
               if ($select_accounts->rowCount() > 0) {
                  while ($fetch_accounts = $select_accounts->fetch(PDO::FETCH_ASSOC)) {
                     $adminName = $fetch_accounts['fname'] . ' ' . $fetch_accounts['sname']; // Concatenate fname and sname
               ?>
                     <tr>
                        <td><?= $fetch_accounts['id']; ?></td>
                        <td><?= $adminName; ?></td>
                        <td>
                           <a href="admin_accounts.php?delete=<?= $fetch_accounts['id']; ?>" onclick="return confirm('ARE YOU SURE YOU WANT TO DELETE THIS ACCOUNT?')" class="delete">DELETE</a>
                           <?php
                           if ($fetch_accounts['id'] == $admin_id) {
                              echo '<a href="update_profile.php" class="option">UPDATE</a>';
                           }
                           ?>
                        </td>
                     </tr>
               <?php
                  }
               } else {
                  echo '<tr><td colspan="3" class="empty">No accounts available!</td></tr>';
               }
               ?>
            </tbody>
         </table>
      </div>
   </div>
</section>

<script src="../js/admin_script.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js"></script>
<script>
   $(document).ready(function() {
      $('#adminTable').DataTable();
   });
</script>
</body>
</html>
