<?php
session_start();
require_once "connection.php";
require_once "modals.php";

//SIGN UP 
if (isset($_POST['signupBtn'])) {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $birthday = $_POST['birthday'];
    $contactNumber = $_POST['contactNumber'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $querySignup = "INSERT INTO `admins`(`first_name`, `last_name`, `birthday`, `contact_no`, `email`, `password`, `status`) VALUES ('" . $firstName . "','" . $lastName . "','" . $birthday . "','" . $contactNumber . "','" . $email . "','" . $password_hash . "', 'Offline')";
   
    if (executeQuery($querySignup)) {
        header('Location: index.php');
    }
}

// LOGIN BTN 

if (isset($_POST['loginBtn'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    //$hash = password_hash($password, PASSWORD_DEFAULT);

    $queryLogin = "SELECT * FROM `admins` WHERE email = '" . $email . "'";
    $accounts = executeQuery($queryLogin);

    if (mysqli_num_rows($accounts) > 0) {
        while ($account = mysqli_fetch_assoc($accounts)) {
            if (password_verify($password, $account['password'])) {
                $_SESSION['admin_id'] = $account['admin_id'];
                $_SESSION['firstName'] = $account['first_name'];
                $_SESSION['lastName'] = $account['last_name'];
                $_SESSION['birthday'] = $account['birthday'];
                $_SESSION['contactNumber'] = $account['contact_no'];
                $_SESSION['email'] = $account['email'];
                $_SESSION['admin_type_id'] = $account['admin_type_id'];

                $updateStatus = "UPDATE `admins` SET `status` = 'Online' WHERE `admin_id` = '".$_SESSION['admin_id']."'";
                executeQuery($updateStatus);
                if ($_SESSION['admin_type_id'] == 1) {
                    header('Location: dashboard.php');
                } else if (($_SESSION['admin_type_id'] == 2)) {
                    header('Location: dashboard.php');
                } else if ($_SESSION['admin_type_id'] == 3) {
                    header('Location: addEntry.php');
                } else {
                    echo '<script>
                        Swal.fire({
                            icon: "info",
                            title: "Hold on",
                            html:
                                "Your account is not yet verified. Please wait for a confirmation email to start using this system. For more information, click " + "<a href=\'FAQs.php\'>here</a>",
                            }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "./index.php";
                            }
                          });
                        </script>';
                }
            } else {
                echo '<script>
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Invalid email/password!"
                          }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "./index.php";
                            }
                          });
                        </script>';
            }
        }
    } else {
        echo '<script>
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "No account was found. Click Sign up to create one."
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "./index.php";
                }
            });
            </script>';
    }
}


// CHANGE PW
if (isset($_POST["updatePassBtn"])) {
    $currentpassword = $_POST["currentPassword"];
    $newpassword = $_POST['newPassword'];
    $confirmpassword = $_POST['confirmPassword'];


    if (!empty($currentpassword) || !empty($newPassword) ||  !empty($confirmpassword)) {

        $passwordquery = "SELECT * FROM `admins` WHERE `email` ='" . $_SESSION['email'] . "'";

        $passwordresult = executeQuery($passwordquery);
        $count = mysqli_num_rows($passwordresult);

        if ($count == 1) {
            while ($row = mysqli_fetch_assoc($passwordresult)) {

                $fetchpassword = $row['password'];

                if (!password_verify($currentpassword, $fetchpassword)) {
                    echo '<script>alert("Invalid current password!")</script>';
                } elseif ($newpassword == $currentpassword) {
                    echo '<script>alert("New password is the same as current password!")</script>';
                } else if ($newpassword != $confirmpassword) {
                    echo '<script>alert("New password doe not match with confirm password!")</script>';
                } else if (password_verify($currentpassword, $fetchpassword)) {

                    if ($newpassword == $confirmpassword) {
                        $password_raw = $_POST['newPassword'];
                        $number = preg_match('@[0-9]@', $password_raw);
                        $uppercase = preg_match('@[A-Z]@', $password_raw);
                        $lowercase = preg_match('@[a-z]@', $password_raw);
                        $specialChars = preg_match('@[^\w]@', $password_raw);

                        if (strlen($password_raw) < 8 || !$number || !$uppercase || !$lowercase || !$specialChars) {
                            echo '<script>alert("Password not strong! Use a combination of uppercase, lowercase, numbers and special characters.")</script>';
                        } else if (strlen($password_raw) > 8) {
                            $hash = password_hash($password_raw, PASSWORD_DEFAULT);

                            $updatequery = "UPDATE `admins` SET `password`='" . $hash . "' WHERE `email` ='" . $_SESSION['email'] . "'";
                            executeQuery($updatequery);
                            header("Location: index.php");
                        } else {
                            echo '<script>alert("may mali")</script>';
                        }
                    } else {
                        echo '<script>alert("Invalid confirm password!")</script>';
                    }
                }
            }
        }
    } else {
        echo '<script>alert("All fields are required!")</script>';
    }
}


// FORGOT PASSWORD
if (isset($_POST["resetBtn"])) {
    $emailquery = "SELECT * FROM `admins` WHERE `email` = '" . $_POST['email'] . "'";

    $emailresult = executeQuery($emailquery);
    $count = mysqli_num_rows($emailresult);


    if ($count == 1) {

        while ($row = mysqli_fetch_assoc($emailresult)) {
            $email = $row['email'];

            if ($_POST['email'] == $email) {

                if ($_POST['newPassword'] == $_POST['confirmPassword']) {

                    $password_raw = $_POST['newPassword'];
                    $number = preg_match('@[0-9]@', $password_raw);
                    $uppercase = preg_match('@[A-Z]@', $password_raw);
                    $lowercase = preg_match('@[a-z]@', $password_raw);
                    $specialChars = preg_match('@[^\w]@', $password_raw);

                    if (strlen($password_raw) < 8 || !$number || !$uppercase || !$lowercase || !$specialChars) {

                        echo '<script>alert("Password not strong! Use a combination of uppercase, lowercase, numbers and special characters.")</script>';
                    } else {

                        $hash = password_hash($password_raw, PASSWORD_DEFAULT);

                        $updatequery = "UPDATE `admins` SET `password`='" . $hash . "' WHERE `email` ='" . $_POST['email'] . "'";
                        executeQuery($updatequery);
                        echo '<script>alert("Password changed successfully!")</script>';
                    }
                } else {
                    echo '<script>alert("Invalid confirm password!")</script>';
                }
            }
        }
    } else {
        echo '<script>alert("All fields are required!")</script>';
    }
}


// UPDATE PROFILE SETTINGS - bday cannot be changed since fixed naman na siya dapat talaga
if (isset($_POST["editProfileBtn"])){

    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['newEmail'];
    $contactNo= $_POST['contactNo'];

    
    $bdayquery = "SELECT * FROM `admins` WHERE `birthday` = '" . $_POST['birthday'] . "'";

    $bdayresult = executeQuery($bdayquery);
    $row = mysqli_fetch_assoc($bdayresult); 

    $bday = $row['birthday'];
    
    if ($_POST['birthday'] == $bday) {

        $profileQuery = " UPDATE `admins` SET `first_name` = '" . $firstName . "' , last_name = '" . $lastName . "' , email = '" . $email . "' , contact_no = '" . $contactNo . "'  WHERE `birthday` = '$_SESSION[birthday]' ";
        executeQuery($profileQuery);
        echo '<script>alert("Profile details were successfully changed!")</script>';

    } else {
        echo '<script>alert("Incorrect birthday!")</script>';
    }

}