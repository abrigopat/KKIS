<?php
session_start();
require "connection.php";
require "modals.php";

$queryUnemployed = "SELECT *, CONCAT(`last_name`, ', ', `first_name`, ' ', `middle_name`) AS  full_name, DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), birthday)), '%Y') + 0 AS age, employment_info.employment_status FROM `employment_info` RIGHT JOIN `residents` ON employment_info.resident_id = residents.resident_id WHERE `employment_status` = 'Unemployed' "; //query to select all data from table
$queryUnemployedResult = executeQuery($queryUnemployed); //execute query

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Unemployed</title>

    <!-- Custom Page Stylesheets -->
    <link rel="stylesheet" href="assets/scss/viewModal.css">
    <link rel="stylesheet" href="assets/scss/profiles.css">
    <!-- <link rel="stylesheet" href="assets/scss/mediaquery.css"> -->
    <!-- <link rel="stylesheet" href="assets/scss/modal.css"> -->
    <link rel="stylesheet" href="assets/scss/sideMenu.css">

    <!-- Bootstrap-Select -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">

    <!-- DATA TABLES CDN -->
    <link rel="stylesheet" href="assets/css/datatables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/logos/kkis.ico">


</head>

<body>
    <div class="expanded d-none d-lg-flex" id="leftPanel">
        <?php include 'sideMenu.php' ?>
    </div>

    <div class="mainContainer" id="mainPanel">
        <header class="d-lg-none d-flex" id="mdHeader">
            <!-- Append Small Nav here -->
        </header>
        <header class="d-none d-lg-block" id="lgHeader">
            <div class="row mx-0" id="headerRow">
                <div class="col-md-8 col-12 d-flex justify-content-start align-items-center">
                    <i class="fa-solid fa-arrow-left me-4" role="button" id="backPage"></i>
                    <span class="pageTitle">Unemployed</span>
                </div>

                <div class="col-md-4 d-none d-md-flex justify-content-end align-items-center">
                    <span class="accountType">Super Admin Account</span>
                </div>
            </div>
            <hr id="headerHR">
        </header>

        <!-- Content Start -->
        <div class="container-fluid content" id="contentID">
            <div class="sectionDiv">
                <!-- Kabataan Table Start -->
                <div class="sectionDiv mx-0 mt-4" id="sectionKabataan">
                    <!-- Options Rows -->
                    <div class="optionRow row gx-5 m-0 mb-1">
                        <!-- Show No. of Rows -->
                        <div class="col-lg-12 col-md-4 col-sm-6 col-12 d-flex align-items-center my-1 p-0" id="kabataanLength"></div>
                        <!-- Output Buttons -->
                        <div class="d-none col-lg-6 d-lg-flex justify-content-lg-start p-0" id="kabataanOutput"></div>
                        <!-- Filter -->
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12 d-flex align-items-center">
                        </div>
                        <!-- Search -->
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12 p-0" id="kabataanSearch">

                        </div>
                    </div>

                    <!-- Actual table -->
                    <table class="table table-stripped table-bordered dataTable responsive display nowrap no-footer dtr-inline collapsed printTable" role="grid" cellspacing="0" id="kabataanTbl" style="width:100%">
                        <thead class="tblHeadRow">
                            <tr>
                                <th class="tblHead">Name</th>
                                <th class="tblHead">Gender</th>
                                <th class="tblHead">Age</th>
                                <th class="tblHead">Birthdate</th>
                                <th class="tblHead">Civil status</th>
                                <th class="tblHead">Contact No.</th>
                                <th class="tblHead">Purok</th>
                                <th class="tblHead">Employer Type</th>
                                <th class="tblHead actions">Actions</th>
                            </tr>
                        </thead>

                        <thead id="kabataanTblHead">
                            <tr>
                                <!-- <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th> -->
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            while ($unemployed = mysqli_fetch_array($queryUnemployedResult)) {
                                $unemployedID = $unemployed["resident_id"]; //get resident id
                                $unemployedEID = $unemployed["rencrypted_id"];
                            ?>

                                <tr>

                                    <td id="fullName" class="entryRow" scope="col" data-label="Name"><?php echo $unemployed["full_name"]; ?></td>

                                    <td id="gender" class="entryRow" scope="col" data-label="Gender"><?php echo $unemployed["gender_preference"]; ?></td>

                                    <td id="age" class="entryRow" scope="col" data-label="Age"><?php echo $unemployed["age"]; ?></td>

                                    <td id="birthday" class="entryRow" scope="col" data-label="Birthdate"><?php echo $unemployed["birthday"]; ?></td>

                                    <td id="civilStatus" class="entryRow" scope="col" data-label="Civil status"><?php echo $unemployed["marital_status"]; ?></td>

                                    <td id="contactNo" class="entryRow" scope="col" data-label="Contact No."><?php echo $unemployed["contact_no"]; ?></td>


                                    <td id="purok" class="entryRow" scope="col" data-label="Purok"><?php echo $unemployed["purok"]; ?></td>

                                    <td id="employer_type" class="entryRow" scope="col" data-label="Employer Type"><?php echo $unemployed["remarks"]; ?></td>

                                    <td id="actions" class="entryRow" scope="col" data-label="Actions">
                                        <div class="row mx-0 p-2">
                                            <!-- View More -->
                                            <div class="col-lg-12 col-12 d-flex justify-content-center align-items-center p-0">
                                                <a class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#viewMore<?php echo $unemployed["resident_id"]; ?>" data-id="<?php echo $unemployed["resident_id"]; ?>" href="#viewMore">View More</a>
                                            </div>
                                        </div>
                                    </td>

                                </tr>

                                <!-- Kabataan Modal -->
                                <div class="modal" role="dialog" tabindex="1" id="viewMore<?php echo $unemployed["resident_id"]; ?>">
                                    <div class="modal-dialog modal-xl modal-dialog-scrollable" id="modalDialogID" role="document">
                                        <div class="modal-content" id="modalContentID">
                                            <div class="modal-header" id="modalHeaderID">
                                                <div class="d-flex justify-content-start align-items-start">
                                                    <img src="assets/img/logos/kkis-brand.svg" alt="" width="50px" height="auto">
                                                    <span class="ms-1 modal-title" id="modalTitle">KKIS</span>
                                                </div>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <hr id="modalHR">
                                            <div class="modal-body m-0" id="modalBodyID">
                                            <!-- Basic Info -->
                                            <div class="row mx-3 px-3" width="100%" height="100%" style="border: 1px solid #aeaeae; border-radius: 20px;">
                                                <!-- Text -->
                                                <div class="col-md-8 col-12 d-flex align-items-center">
                                                    <div>
                                                        <span class="basicTxt w-100 my-1" id="nameTxt"><?php echo $unemployed["full_name"]; ?></span>
                                                        <br>
                                                        <span class="basicTxt w-100 my-1 mt-2" id="ageGender"><?php echo $unemployed["age"] . " YEARS OLD, " . $unemployed["gender_preference"]; ?></span>
                                                        <br>
                                                        <span class="basicTxt w-100 my-1" id="purok"><?php echo $unemployed["purok"]; ?></span>
                                                    </div>
                                                </div>
                                                <!-- QR Code -->
                                                <div class="col-12 col-md-4 d-flex justify-content-md-end justify-content-center">
                                                    <img class="rounded-2" src="<?php echo $unemployed["qr_code"]; ?>" width="200px" height="auto">
                                                </div>
                                            </div>

                                            <!-- Personal Info -->
                                            <div class="row mx-3 px-3 my-5" width="100%" height="auto" style="border: 1px solid #aeaeae; border-radius: 20px;">
                                                <div class="col-12 titleCol">
                                                    <div class="titleDiv  rounded-pill d-flex justify-content-center align-items-center">
                                                        <span class="rowTitle">Personal Information</span>
                                                    </div>
                                                </div>

                                                <br>
                                                <div class="col-md-2 col-6">
                                                    <label class="form-label modalLabel">Birthday:&nbsp;</label>
                                                </div>
                                                <div class="col-md-3 col-6">
                                                    <p class="textRetrieved" id="textRetrievedID"><?php echo $unemployed["birthday"]; ?></p>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <label class="form-label modalLabel">Voter Type:&nbsp;</label>
                                                </div>
                                                <div class="col-md-4 col-6">
                                                    <p class="textRetrieved" id="textRetrievedID"><?php echo $unemployed["voter_type"]; ?></p>
                                                </div>

                                                <div class="col-md-2 col-6">
                                                    <label class="form-label modalLabel">Birthplace:&nbsp;</label>
                                                </div>
                                                <div class="col-md-3 col-6">
                                                    <p class="textRetrieved" id="textRetrievedID"><?php echo $unemployed["birthplace"]; ?></p>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <label class="form-label modalLabel">Contact No:&nbsp;</label>
                                                </div>
                                                <div class="col-md-4 col-6">
                                                    <p class="textRetrieved" id="textRetrievedID"><?php echo $unemployed["contact_no"]; ?></p>
                                                </div>

                                                <div class="col-md-2 col-6">
                                                    <label class="form-label modalLabel">Marital Status:&nbsp;</label>
                                                </div>
                                                <div class="col-md-3 col-6">
                                                    <p class="textRetrieved" id="textRetrievedID"><?php echo $unemployed["marital_status"]; ?></p>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <label class="form-label modalLabel">Religion:&nbsp;</label>
                                                </div>
                                                <div class="col-md-4 col-6">
                                                    <p class="textRetrieved" id="textRetrievedID"><?php echo $unemployed["religion"]; ?></p>
                                                </div>

                                                <div class="col-md-2 col-6">
                                                    <label class="form-label modalLabel">Disability:&nbsp;</label>
                                                </div>
                                                <div class="col-md-3 col-6">
                                                    <p class="textRetrieved" id="textRetrievedID"><?php echo $unemployed["disability"]; ?></p>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <label class="form-label modalLabel">Organization/s (if any):&nbsp;</label>
                                                </div>
                                                <div class="col-md-4 col-6">
                                                    <p class="textRetrieved" id="textRetrievedID"><?php echo $unemployed["organization"]; ?></p>
                                                </div>

                                                <div class="col-md-2 col-6">
                                                    <label class="form-label modalLabel">House Address:&nbsp;</label>
                                                </div>
                                                <div class="col-md-3 col-6">
                                                    <p class="textRetrieved" id="textRetrievedID"><?php echo $unemployed["house_address"]; ?></p>
                                                </div>

                                                <div class="col-md-3 col-6">
                                                    <label class="form-label modalLabel">Remarks:&nbsp;</label>
                                                </div>
                                                <div class="col-md-4 col-6">
                                                    <p class="textRetrieved" id="textRetrievedID"><?php echo $unemployed["remarks"]; ?></p>
                                                </div>
                                            </div>

                                            <!-- Status -->
                                            <div class="row mx-3 px-3 my-5" width="100%" height="auto" style="border: 1px solid #aeaeae; border-radius: 20px;">
                                                <div class="col-12 titleCol">
                                                    <div class="titleDiv  rounded-pill d-flex justify-content-center align-items-center">
                                                        <span class="rowTitle">Status</span>
                                                    </div>
                                                </div>


                                            </div>

                                            <!-- House Dec -->
                                            <div class="row mx-3 px-3 my-5" width="100%" height="auto" style="border: 1px solid #aeaeae; border-radius: 20px;">
                                                <div class="col-12 titleCol">
                                                    <div class="titleDiv  rounded-pill d-flex justify-content-center align-items-center">
                                                        <span class="rowTitle">House Declaration</span>
                                                    </div>
                                                </div>


                                            </div>

                                            <!-- old Status -->
                                            <div class="row rowContainer my-4 mx-2" id="modalRow2">
                                                <div class="col col-12 p-0">
                                                    <div class="markerContainer px-3" id="row2Header">
                                                        <p class="modalMarker" id="row2Title">Status</p>
                                                    </div>
                                                </div>

                                                <?php
                                                $employmentCheck = "SELECT * FROM employment_info WHERE resident_id = $unemployedID"; //check if resident has employment info
                                                $employmentCheckResult = executeQuery($employmentCheck);

                                                $educationCheck = "SELECT * FROM educational_info WHERE resident_id = $unemployedID"; //check if resident has educational info
                                                $educationCheckResult = executeQuery($educationCheck);

                                                if ($employment = mysqli_fetch_array($employmentCheckResult)) { //checking if the user is an employee
                                                ?>
                                                    <div class="col" id="row2Body">
                                                        <div class="row gy-1" id="row2Content">
                                                            <div class="col-6" id="row2ContentDiv">
                                                                <label class="form-label modalLabel">Employment Status:</label>
                                                                <p class="textRetrieved" id="textRetrievedID"><?php echo $employment["employment_status"]; ?></p>
                                                            </div>
                                                            <div class="col-6" id="row2ContentDiv">
                                                                <label class="form-label modalLabel">Employee Type:</label>
                                                                <p class="textRetrieved" id="textRetrievedID"><?php echo $employment["employee_type"]; ?></p>
                                                            </div>
                                                            <div class="col-6" id="row2ContentDiv">
                                                                <label class="form-label modalLabel">Public/Private:</label>
                                                                <p class="textRetrieved" id="textRetrievedID"><?php echo $employment["employer_type"]; ?></p>
                                                            </div>
                                                            <div class="col-6" id="row2ContentDiv">
                                                                <label class="form-label modalLabel">Name of Employer/Company:</label>
                                                                <p class="textRetrieved" id="textRetrievedID"><?php echo $employment["employer_name"]; ?></p>
                                                            </div>

                                                            <?php
                                                            $industry_id = $employment["industry_id"]; // store industry_id to variable
                                                            $salary_id = $employment["salary_id"]; // store salary_id to variable

                                                            $industryCheck = "SELECT * FROM industries WHERE industry_id='$industry_id'";
                                                            $queryIndustryCheck = mysqli_query($conn, $industryCheck);

                                                            $salaryCheck = "SELECT * FROM salaries WHERE salary_id='$salary_id'";
                                                            $querySalaryCheck = mysqli_query($conn, $salaryCheck);
                                                            ?>

                                                            <div class="col-6" id="row2ContentDiv">
                                                                <label class="form-label modalLabel">Occupation:</label>
                                                                <p class="textRetrieved" id="textRetrievedID">
                                                                    <?php
                                                                    $industry = mysqli_fetch_array($queryIndustryCheck);
                                                                    echo $industry["description"];
                                                                    ?>
                                                                </p>
                                                            </div>

                                                            <div class="col-6" id="row2ContentDiv">
                                                                <label class="form-label modalLabel">Salary Range:</label>
                                                                <p class="textRetrieved" id="textRetrievedID">
                                                                    <?php
                                                                    $salary = mysqli_fetch_array($querySalaryCheck);
                                                                    echo $salary["description"];
                                                                    ?>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <?php
                                                } else if ($education = mysqli_fetch_array($educationCheckResult)) { //checking if the user is a student

                                                    if ($education["student_status"] == "ENROLLED") {
                                                    ?>
                                                        <div class="col" id="row2Body">
                                                            <div class="row gy-1" id="row2Content">
                                                                <div class="col-6" id="row2ContentDiv">
                                                                    <label class="form-label modalLabel">Student Status:</label>
                                                                    <p class="textRetrieved" id="textRetrievedID"><?php echo $education["student_status"]; ?></p>
                                                                </div>
                                                                <div class="col-6" id="row2ContentDiv">
                                                                    <label class="form-label modalLabel">Student Level:</label>
                                                                    <p class="textRetrieved" id="textRetrievedID"><?php echo $education["student_level"]; ?></p>
                                                                </div>
                                                                <div class="col-6" id="row2ContentDiv">
                                                                    <label class="form-label modalLabel">Public/Private:</label>
                                                                    <p class="textRetrieved" id="textRetrievedID"><?php echo $education["school_type"]; ?></p>
                                                                </div>
                                                                <div class="col-6" id="row2ContentDiv">
                                                                    <label class="form-label modalLabel">Name of School/University:</label>
                                                                    <p class="textRetrieved" id="textRetrievedID"><?php echo $education["school_name"]; ?></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php
                                                    } else if ($education["student_status"] == "OUT-OF-SCHOOL YOUTH") {
                                                    ?>
                                                        <div class="col" id="row2Body">
                                                            <div class="row gy-1" id="row2Content">
                                                                <div class="col-6" id="row2ContentDiv">
                                                                    <label class="form-label modalLabel">Student Status:</label>
                                                                    <p class="textRetrieved" id="textRetrievedID"><?php echo $education["student_status"]; ?></p>
                                                                </div>
                                                                <div class="col-6" id="row2ContentDiv">
                                                                    <label class="form-label modalLabel">Student Level:</label>
                                                                    <p class="textRetrieved" id="textRetrievedID"><?php echo $education["student_level"]; ?></p>
                                                                </div>
                                                                <div class="col-6" id="row2ContentDiv">
                                                                    <label class="form-label modalLabel">Public/Private:</label>
                                                                    <p class="textRetrieved" id="textRetrievedID"><?php echo $education["school_type"]; ?></p>
                                                                </div>
                                                                <div class="col-6" id="row2ContentDiv">
                                                                    <label class="form-label modalLabel">Name of School/University:</label>
                                                                    <p class="textRetrieved" id="textRetrievedID"><?php echo $education["school_name"]; ?></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <div class="col" id="row2Body">
                                                            <div class="row gy-1" id="row2Content">
                                                                <div class="col-6" id="row2ContentDiv">
                                                                    <label class="form-label modalLabel">Student Status:</label>
                                                                    <p class="textRetrieved" id="textRetrievedID"><?php echo $education["student_status"]; ?></p>
                                                                </div>
                                                                <div class="col-6" id="row2ContentDiv">
                                                                    <label class="form-label modalLabel">Student Level:</label>
                                                                    <p class="textRetrieved" id="textRetrievedID"><?php echo $education["student_level"]; ?></p>
                                                                </div>
                                                                <div class="col-6" id="row2ContentDiv">
                                                                    <label class="form-label modalLabel">Public/Private:</label>
                                                                    <p class="textRetrieved" id="textRetrievedID"><?php echo $education["school_type"]; ?></p>
                                                                </div>
                                                                <div class="col-6" id="row2ContentDiv">
                                                                    <label class="form-label modalLabel">Name of School/University:</label>
                                                                    <p class="textRetrieved" id="textRetrievedID"><?php echo $education["school_name"]; ?></p>
                                                                </div>

                                                                <?php

                                                                $industry_id = $education["industry_id"]; // store industry_id to variable
                                                                $salary_id = $education["salary_id"]; // store salary_id to variable

                                                                $industryCheck = "SELECT * FROM industries WHERE industry_id='$industry_id'";
                                                                $queryIndustryCheck = mysqli_query($conn, $industryCheck);

                                                                $salaryCheck = "SELECT * FROM salaries WHERE salary_id='$salary_id'";
                                                                $querySalaryCheck = mysqli_query($conn, $salaryCheck);
                                                                ?>

                                                                <div class="col-6" id="row2ContentDiv">
                                                                    <label class="form-label modalLabel">Occupation:</label>
                                                                    <p class="textRetrieved" id="textRetrievedID">
                                                                        <?php
                                                                        $industry = mysqli_fetch_array($queryIndustryCheck);
                                                                        echo $industry["description"];
                                                                        ?>
                                                                    </p>
                                                                </div>

                                                                <div class="col-6" id="row2ContentDiv">
                                                                    <label class="form-label modalLabel">Salary Range:</label>
                                                                    <p class="textRetrieved" id="textRetrievedID">
                                                                        <?php
                                                                        $salary = mysqli_fetch_array($querySalaryCheck);
                                                                        echo $salary["description"];
                                                                        ?>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php
                                                    }
                                                    ?>
                                                <?php
                                                }
                                                $household_id = $unemployed["household_id"]; //getting the household id of the user
                                                $householdCheck = "SELECT *, CONCAT(`head_first_name`, ', ', `head_first_name`, ' ', `head_middle_name`) AS `head_name` FROM households WHERE household_id = $household_id"; //getting the household details of the user
                                                $householdCheckResult = executeQuery($householdCheck);
                                                ?>
                                            </div>

                                            <div class="row rowContainer my-4 mx-2" id="modalRow2">
                                                <div class="col col-12 p-0">
                                                    <div class="markerContainer px-3" id="row2Header">
                                                        <p class="modalMarker" id="row2Title">House Declaration</p>
                                                    </div>
                                                </div>
                                                <div class="col" id="row2Body">
                                                    <div class="row gy-1" id="row4Content">
                                                        <?php
                                                        while ($household = mysqli_fetch_array($householdCheckResult)) {
                                                        ?>
                                                            <div class="col col-12" id="row4ContentDiv">
                                                                <label class="form-label modalLabel">Head of the family:</label>
                                                                <p class="textRetrieved" id="FamHead"><?php echo $household["head_name"]; ?></p>
                                                            </div>
                                                            <div class="col col-12" id="row4ContentDiv">
                                                                <label class="form-label modalLabel">Remarks:</label>
                                                                <p class="textRetrieved" id="textRetrievedID1"><?php echo $unemployed["remarks"]; ?></p>
                                                            </div>
                                                            <div class="col col-12" id="row4ContentDiv">
                                                                <label class="form-label modalLabel">No. of members:</label>
                                                                <p class="textRetrieved" id="textRetrievedID1"><?php echo $household["members_count"]; ?></p>
                                                            </div>
                                                        <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            <?php
                            };
                            ?>
                        </tbody>
                        <tfoot>
                            <tr id="kabataanFooter">
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap-select -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

    <!-- Data tables -->
    <!-- <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script> -->
    <script src="assets/js/datatables.min.js"></script>
    <script src="assets/js/pdfmake.min.js"></script>
    <script src="assets/js/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>

    <!-- Custom Script -->
    <script type="text/javascript" src="assets/js/profiles.js"></script>
    <script type="text/javascript" src="assets/js/sideMenu.js"></script>

    <!-- Active Link -->
    <script type="text/javascript">
        $(document).ready(function() {
            $("#dashboard-link").addClass('nav-active');
            $("#dashboard-md-link").addClass("nav-md-active");
        })
    </script>
</body>

</html>