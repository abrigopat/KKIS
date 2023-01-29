<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings</title>

    <!-- Fundamental links -->
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />

    <!-- Custom Stylesheets -->
    <link rel="stylesheet" href="assets/scss/settings.css">
    <link rel="stylesheet" href="assets/scss/changePW.css">
    <link rel="stylesheet" href="assets/scss/mediaquery.css">

    <!-- DATA TABLES CDN -->
    <link rel="stylesheet" href="assets/css/datatables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/logos/kkis.ico">
</head>

<body>
    <div class="container-fluid content">
        <div class="sectionDiv">
            <div>
                <h3 class="accSettingsHeading ms-3 mt-1 mb-5 ps-3 pe-3">
                    Account Settings
                </h3>
            </div>

            <div class="account-settings m-3 ps-3 pe-3">

                <!-- START OF EDIT PROFILE -->
                <div class="editProfilePanel">
                    <h4 class="mb-4">Edit Profile</h4>

                    <div class="gradient">
                    </div>

                    <!-- FORM FOR EDIT PROFILE -->
                    <div class="mt-3">
                        <form id="editProfileForm" action="" onSubmit="return passValidate();" method="post">
                            <div class="row">
                                <!-- Left Column -->
                                <div class="col">
                                    <?php
                                    $admins_query = "SELECT * FROM `admins`";
                                    $admins_query_run = executeQuery($admins_query);

                                    $firstName = $_POST['firstName'];
                                    $lastName = $_POST['lastName'];
                                    $email = $_POST['newEmail'];
                                    $contactNo = $_POST['contactNo'];
                                    
                                    ?>

                                    <div class="mt-3 row">
                                        <label for="firstName" class="col-xl-3 col-lg-12 col-form-label">First Name</label>
                                        <div class="col-xl-8 col-lg-12">
                                            <input type="text" class="form-control" name="firstName" id="firstName" value="<?php echo $firstName ?>">
                                        </div>
                                    </div>

                                    <div class="mt-3 row">
                                        <label for="lastName" class="col-xl-3 col-lg-12 col-form-label">Last Name</label>
                                        <div class="col-xl-8 col-lg-12">
                                            <input type="text" class="form-control" name="lastName" id="lastName" value="<?php echo $lastName ?>">
                                        </div>
                                    </div>

                                    <div class="mt-3 row">
                                        <label for="middleName" class="col-xl-3 col-lg-12 col-form-label">Birthday</label>
                                        <div class="col-xl-8 col-lg-12">
                                            <input class="form-control" type="date" name="birthday" value="<?php echo $birthday ?>" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Column col-xxl-6 col-xl-6 -->
                                <div class="col-sm-6">
                                    <div class="mt-3 row">
                                        <label for="newEmail" class="col-xl-3 col-lg-12 col-form-label">New Email</label>
                                        <div class="col-xl-9 col-lg-12">
                                            <input type="email" class="form-control" name="newEmail" value="<?php echo $email ?>">
                                        </div>
                                    </div>

                                    <div class="mt-3 row">
                                        <label for="email" class="col-xl-3 col-lg-12 col-form-label">Contact Number</label>
                                        <div class="col-xl-9 col-lg-12">
                                            <input type="numerals" class="form-control" name="contactNo" id="contactNo" value="<?php echo $contactNo ?>">
                                        </div>
                                    </div>
                                </div>

                                <!-- Save Button -->
                                <div class="row m-0 mt-3 mb-3" id="rowBtn">
                                    <div class="col-12 d-flex justify-content-end align-items-end">
                                        <button class="btn editProfileBtn rounded-pill mt-4" name="editProfileBtn" type="submit" form="editProfileForm" id="editProfileBtn">
                                                Save
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>

                <!-- START OF CHANGE PASSWORD -->
                <div class="changePassPanel mt-4">
                    <h4 class="mb-4">Update Password</h4>

                    <div class="gradient">
                    </div>

                    <!-- FORM FOR CHANGE PASSWORD -->
                    <div class="mt-3">
                        <form id="updatePassForm" action="" onSubmit="return passValidate();" method="post">
                            <div class="row">
                                <!-- Left Column -->
                                <div class="col">
                                    <div class="mt-3 row">
                                        <label for="currentPassword" class="col-xxl-3 col-xl-5 col-lg-3 col-md-3 col-sm-4 col-form-label">Current Password</label>
                                        <div class="col-xxl-8 col-xl-6 col-lg-8 col-md-8 col-sm-8">
                                            <input class="form-control" type="password" name="currentPassword" id="currentPassword" required>

                                            <i class="toggle-password far fa-eye-slash" id="toggle_current_pass" style="color:#393737;" toggle="#password-field"></i>

                                            <script>
                                                const togglePassword = document.querySelector('#toggle_current_pass');
                                                const password = document.querySelector('#currentPassword');

                                                togglePassword.addEventListener('click', function(e) {
                                                    // toggle the type attribute
                                                    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                                                    password.setAttribute('type', type);
                                                    // toggle the eye slash icon
                                                    this.classList.toggle('fa-eye');
                                                });
                                            </script>
                                        </div>
                                    </div>

                                    <div class="mt-3 row">
                                        <label for="newPassword" class="col-xxl-3 col-xl-5 col-lg-3 col-md-3 col-sm-4 col-form-label">New Password</label>
                                        <div class="col-xxl-8 col-xl-6 col-lg-8 col-md-8 col-sm-8">
                                            <input class="form-control" type="password" name="newPassword" id="newPassword" required>
                                        
                                            <i class="toggle-password far fa-eye-slash" id="toggle_new_pass" style="color:#393737;" toggle="#password-field"></i>

                                            <script>
                                                const toggleNewPassword = document.querySelector('#toggle_new_pass');
                                                const newPassword = document.querySelector('#newPassword');

                                                toggleNewPassword.addEventListener('click', function(e) {
                                                    // toggle the type attribute
                                                    const type = newPassword.getAttribute('type') === 'password' ? 'text' : 'password';
                                                    newPassword.setAttribute('type', type);
                                                    // toggle the eye slash icon
                                                    this.classList.toggle('fa-eye');
                                                });
                                            </script>
                                        </div>
                                    </div>

                                    <div class="mt-3 row">
                                        <label for="confirmPassword" class="col-xxl-3 col-xl-5 col-lg-3 col-md-3 col-sm-4 col-form-label">Confirm Password</label>
                                        <div class="col-xxl-8 col-xl-6 col-lg-8 col-md-8 col-sm-8">
                                            <input class="form-control" type="password" name="confirmPassword" id="confirmPassword" required>
                                        
                                            <i class="toggle-password far fa-eye-slash" id="toggle_confirm_pass" style="color:#393737;" toggle="#password-field"></i>

                                            <script>
                                                const toggleConfirmPassword = document.querySelector('#toggle_confirm_pass');
                                                const confirmPassword = document.querySelector('#confirmPassword');

                                                toggleConfirmPassword.addEventListener('click', function(e) {
                                                    // toggle the type attribute
                                                    const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
                                                    confirmPassword.setAttribute('type', type);
                                                    // toggle the eye slash icon
                                                    this.classList.toggle('fa-eye');
                                                });
                                            </script>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Column -->
                                <div class="col-xxl-6 col-xl-6 ">
                                    <div class="mt-3 row">
                                        <p class="noteHeading">
                                            Note:
                                        </p>

                                        <p>
                                            Password update can only be done every three (3) months.
                                            We recommend that you regularly update your password
                                            to prevent unauthorized access to your account.
                                        </p>
                                    </div>
                                </div>

                                <!-- Save Button -->
                                <div class="row m-0 mt-3 mb-3" id="rowBtn">
                                    <div class="col-12 d-flex justify-content-end align-items-end">
                                        <button class="btn updatePWBtn rounded-pill mt-4" name="updatePassBtn" type="submit" form="updatePassForm" id="updatePassBtn">
                                                Update
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
                <!-- END -->
            </div>
        </div>
    </div>


    <!-- Fundamental Links -->
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>

    <!-- Data tables -->
    <script src="assets/js/datatables.min.js"></script>
    <script src="assets/js/pdfmake.min.js"></script>
    <script src="assets/js/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>

    <!-- Custom Script -->
    <script type="text/javascript" src="assets/js/settings.js"></script>

    <!-- Active page -->
    <script type="text/javascript">
        $(document).ready(function () {
            $("#changePass-link").addClass('active');
        });
    </script>
</body>

</html>