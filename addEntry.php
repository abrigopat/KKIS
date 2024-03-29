<?php
require_once "access-staff.php";
require_once "assets/phpqrcode/qrlib.php";
require_once "assets/php-encrypt-decrypt-class-main/Encryption.php";

$userRole = "SELECT `description` FROM `admin_type` WHERE `admin_type_id` = '$_SESSION[admin_type_id]'";
$role = mysqli_fetch_assoc(executeQuery($userRole));

if (isset($_POST['submitBtn'])) {
    // Household Info
    $hFname = $_POST['hFname'];
    $hMname = isset($_POST['hMname']) ?  $_POST['hMname'] : "";
    $hLname = "";
    if (isset($_POST['hSuffix'])) {
        $hLname = $_POST['hLname'] . " " . $_POST['hSuffix'];
    } else if (!isset($_POST['hSuffix'])) {
        $hLname = $_POST['hLname'];
    }
    $membersCount = $_POST['membersCount'];

    //Personal Info
    $residentID;
    $fName = $_POST['fName'];
    $mName = isset($_POST['mName']) ?  $_POST['mName'] : "";
    $lName = "";
    if (isset($_POST['suffix'])) {
        $lName = $_POST['lName'] . " " . $_POST['suffix'];
    } else if (!isset($_POST['suffix'])){
        $lName = $_POST['lName'];
    }
    $gender = $_POST['gender'];
    $birthday = $_POST['birthday'];
    $birthplace = $_POST['birthplace'];
    $mStatus = $_POST['mStatus'];
    $religion = $_POST['religion'];
    $disability = $_POST['disability'];
    $contact = $_POST['contact'];
    $voterType = $_POST['voterType'];
    $address = $_POST['address'];
    $purok = $_POST['purok'];
    $organization = $_POST['organization'];
    $remarks = isset($_POST['remarks']) ? $_POST['remarks'] : "";
    $householdID; //foreign key
    // $date_added = date("Y-m-d");
    $encryptedName = $fName . " " . $lName;

    // Educational Info
    $educStatus = isset($_POST['educStatus']) ? ($_POST['educStatus']) : null;
    $educlevel = isset($_POST['educLevel']) ? ($_POST['educLevel']) : "N/A";
    $schoolType = isset($_POST['schoolType']) ? ($_POST['schoolType']) : "N/A";
    $school = isset($_POST['schoolName']) ? ($_POST['schoolName']) : "N/A";
    $schoolName = mysqli_real_escape_string($conn, $school);
    $educIndustry =  isset($_POST['educIndustry']) ? ($_POST['educIndustry']) : "64"; //foreign key
    $educSalary = isset($_POST['educSalary']) ? ($_POST['educSalary']) : "1"; // foreign key

    // Employment Info
    $employStatus = isset($_POST['employeeStatus']) ? $_POST['employeeStatus'] : null;
    $employeeType = isset($_POST['employeeType']) ? $_POST['employeeType'] : "N/A";
    $employerType = isset($_POST['employerType']) ? $_POST['employerType'] : "N/A";
    $employer = isset($_POST['employerName']) ? $_POST['employerName'] : "N/A";
    $employIndustry = isset($_POST['employIndustry']) ? $_POST['employIndustry'] : "64"; //foreign key
    $employSalary = isset($_POST['employSalary']) ? $_POST['employSalary'] : "1"; //foreign key

    //FOR ENCRYPTED IDs
    $key = 'kkis2023';
    $rEncrypt = $fName . " " . $mName . " " . $lName;
    $hEncrypt = $hFname . " " . $hMname . " " . $hLname;

    $encryptedResident = Encryption::Encode($rEncrypt, $key);
    $encryptedHousehold = Encryption::Encode($hEncrypt, $key);

    //Query for checking if RESIDENT entry already exists
    $checkResidents = "SELECT * FROM `residents` WHERE `rencrypted_id` = '$encryptedResident'";
    $existingResident = executeQuery($checkResidents);

    if ($residentCount = mysqli_num_rows($existingResident) > 0) {
        echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Record already exists!'
          }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = './addEntry.php';
            }
            
          });
        </script>";
    } else {
        //Query for Households
        $queryHousehold = "INSERT INTO `households` (`hencrypted_id`, `head_first_name`, `head_middle_name`, `head_last_name`, `members_count`) VALUES ('" . $encryptedHousehold . "','" . $hFname . "' , '" . $hMname . "', '" . $hLname . "', '" . $membersCount . "')";

        //Query for checking if HOUSEHOLD entry already exists
        $checkHouseholds = "SELECT * FROM `households` WHERE `hencrypted_id` = '$encryptedHousehold'";
        $existingHousehold = executeQuery($checkHouseholds);

        $updateHousehold = "UPDATE `households` SET `members_count` = '$membersCount' WHERE `hencrypted_id` = '$encryptedHousehold'";

        if ($householdCount = mysqli_num_rows($existingHousehold) > 0) {
            //If entry already exists, update the Remarks column and get the updated row ID
            executeQuery($updateHousehold);

            //Then get the household_id of the updated row and pass the value to $householdID
            $retrieveHouseholdID = "SELECT `household_id` FROM `households` WHERE `hencrypted_id` = '$encryptedHousehold'";
            $getHouseholdID = executeQuery($retrieveHouseholdID);
            while ($householdRow = $getHouseholdID->fetch_assoc()) {
                $householdID = $householdRow["household_id"];
            }
        } else {
            //If entry is new, insert to db and get the inserted row id
            executeQuery($queryHousehold);
            $householdID = mysqli_insert_id($conn);
        }

        //FOR QR CODE GENERATION

        $path = 'assets/img/qrCodes/';
        $file = $path . "KKIS-" . $rEncrypt . ".png";
        $ecc = 'Q';
        $pixelSize = 65;
        $frameSize = 2;
        QRcode::png($encryptedResident, $file, $ecc, $pixelSize, $frameSize);

        //ADD LOGO IN QR CODE
        $logoPath = "assets/img/logos/kkis-logo.png";
        $QRImg = imagecreatefrompng($file);
        $logoImg = imagecreatefromstring(file_get_contents($logoPath));

        $QRImgWidth = imagesx($QRImg);
        $QRImgHeight = imagesy($QRImg);

        $logoWidth = imagesx($logoImg);
        $logoHeight = imagesy($logoImg);

        //SCALE THE LOGO TO FIT IN THE QR CODE
        $logoQRWidth = $QRImgWidth / 4.5;
        $scale = $logoWidth / $logoQRWidth;
        $logoQRHeight = $logoHeight / $scale;

        imagecopyresampled($QRImg, $logoImg, $QRImgWidth / 2.5, $QRImgHeight / 2.5, 0, 0, $logoQRWidth, $logoQRHeight, $logoWidth, $logoHeight);

        //SAVE QR CODE WITH LOGO ON IT
        imagepng($QRImg, $file);

        //Query for Personal info
        $queryPersonal = "INSERT INTO `residents`(`rencrypted_id`, `first_name`, `middle_name`, `last_name`, `gender_preference`, `birthday`, `birthplace`, `marital_status`, `religion`, `disability`, `contact_no`, `voter_type`, `house_address`, `purok`, `organization`, `remarks`, `qr_code`, `household_id`) VALUES ('" . $encryptedResident . "','" . $fName . "', '" . $mName . "', '" . $lName . "', '" . $gender . "', '" . $birthday . "', '" . $birthplace . "', '" . $mStatus . "', '" . $religion . "', '" . $disability . "', '" . $contact . "', '" . $voterType . "', '" . $address . "', '" . $purok . "', '" . $organization . "', '" . $remarks . "', '" . $file . "', '" . $householdID . "')";

        //Execute personal info insertion and get the id of the inserted entry
        executeQuery($queryPersonal);
        $residentID = mysqli_insert_id($conn);

        //Query for Educational Info
        $queryEducation = "INSERT INTO `educational_info`(`student_status`, `student_level`, `school_type`, `school_name`, `industry_id`, `salary_id`, `resident_id`) VALUES ('" . $educStatus . "', '" . $educlevel . "', '" . $schoolType . "', '" . $schoolName . "', '" . $educIndustry . "', '" . $educSalary . "', '" . $residentID . "')";

        //Query for Employment Info
        $queryEmployment = "INSERT INTO `employment_info`(`employment_status`, `employee_type`, `employer_type`, `employer_name`, `industry_id`, `salary_id`, `resident_id`) VALUES ('" . $employStatus . "', '" . $employeeType . "', '" . $employerType . "', '" . $employer . "', '" . $employIndustry . "', '" . $employSalary . "', '" . $residentID . "')";


        //Determine which query to execute based on selected radio button
        if (isset($_POST['educInfo'])) {
            executeQuery($queryEducation);
        } else if (isset($_POST['employInfo'])) {
            executeQuery($queryEmployment);
        } else {
            echo "<script>alert('Something went wrong');</script>";
        }

        if (($queryPersonal && $updateHousehold) && ($queryEducation || $queryEmployment)) {
            echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Entry added successfully!',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = './addEntry.php';
                }
          });
            </script>";
        } else if (($queryPersonal && $queryHousehold) && ($queryEducation || $queryEmployment)) {
            echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Entry added successfully!',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = './addEntry.php';
                }
          });
            </script>";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Add New Entry</title>

    <!-- Bootstrap-Select -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">

    <!-- Custom Stylesheets -->
    <link rel="stylesheet" href="assets/scss/addEntry.css">
    <link rel="stylesheet" href="assets/scss/sideMenu.css">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/logos/kkis.ico">


</head>

<body>

    <div class="expanded d-none d-lg-flex" id="leftPanel">
        <?php
        if ($adminTypeID == 1) {
            include 'sideMenu.php';
        } else if ($adminTypeID == 2) {
            include 'sideMenu-admin.php';
        } else {
            include "sideMenu-staff.php";
        }
        ?>
    </div>

    <div class="mainContainer" id="mainPanel">
        <header class="d-lg-none d-flex" id="mdHeader">
            <!-- Append Small Nav here -->
        </header>
        <header class="d-none d-lg-block" id="lgHeader">
            <div class="row mx-0" id="headerRow">
                <div class="col-md-8 col-12 d-flex justify-content-start align-items-center">
                    <i class="fa-solid fa-bars me-4 d-none" id="hamburger" role="button"></i>
                    <i class="fa-solid fa-xmark me-4" id="close" role="button"></i>
                    <span class="pageTitle">Add New Entry</span>
                </div>

                <div class="col-md-4 d-none d-md-flex justify-content-end align-items-center">
                    <span class="accountType"><?php echo $role["description"] ?></span>
                </div>
            </div>
            <hr id="headerHR">
        </header>

        <!-- Content Start -->
        <div class="container-fluid content">
            <div class="sectionDiv">
                <form method="POST" id="addForm" class="mb-5">
                    <!-- Personal Info -->
                    <div class="card my-4 darkBG">
                        <div class="row m-0">
                            <div class="col col-12 p-0">
                                <div class="d-flex d-xxl-flex align-items-center align-items-xxl-center markerDiv rounded-2" id="markerPersonal">Personal Information</div>
                            </div>
                        </div>
                        <div class="row m-0 my-3 gy-3 gx-3">
                            <div class="col-md-3 col-sm-12 col-12">
                                <label class="col-form-label fieldLabel required w-100 p-1" id="fNameLbl" for="firstName">First Name</label>
                                <input class="form-control userInput text-uppercase w-100" name="fName" id="firstName" type="text" required>
                            </div>
                            <div class="col-md-3 col-12">
                                <label class="col-form-label fieldLabel required w-100 p-1" id="mNameLbl" for="middleName">Middle Name</label>
                                <input class="form-control userInput text-uppercase w-100" name="mName" id="middleName" type="text">
                            </div>
                            <div class="col-md-3 col-sm-8 col-12">
                                <label class="col-form-label fieldLabel required w-100 p-1" id="lNameLbl" for="lastName">Last Name</label>
                                <input class="form-control userInput text-uppercase w-100" name="lName" id="lastName" type="text" required>
                            </div>
                            <div class="col col-lg-1 col-md-3 col-sm-4 col-12">
                                <label class="col-form-label required fieldLabel w-100 p-1" for="suf" id="suffixLbl">Suffix</label>
                                <input class="form-control userInput text-uppercase w-100" val="" name="suffix" type="text" id="suf">
                            </div>
                            <div class="col col-lg-2 col-md-2 col-sm-6 col-12">
                                <label class="col-form-label fieldLabel required w-100 p-1" for="genderPreference">Gender Preference</label>
                                <select class="form-select text-uppercase w-100 personalSelectBox" name="gender" id="genderPreference" required>
                                    <option value="MAN">Man</option>
                                    <option value="WOMAN">Woman</option>
                                    <option value="TRANSGENDER">Transgender</option>
                                    <option value="NON-BINARY/NON-CONFORMING">Non-binary/Non-conforming</option>
                                    <option value="PREFER NOT TO SAY">Prefer not to say</option>
                                </select>
                            </div>
                            <div class="col col-lg-2 col-md-3 col-sm-6 col-12">
                                <label class="col-form-label fieldLabel required w-100 p-1" for="bday">Birthday</label>
                                <input class="form-control text-uppercase w-100 personalSelectBox" name="birthday" id="bday" type="date" required>
                            </div>
                            <div class="col col-lg-2 col-md-4 col-sm-6 col-12">
                                <label class="col-form-label fieldLabel required w-100 p-1" for="bplace">Birthplace</label>
                                <input class="form-control userInput text-uppercase w-100" name="birthplace" id="bplace" type="text" required>
                            </div>
                            <div class="col col-lg-2 col-md-3 col-sm-6 col-12">
                                <label class="col-form-label fieldLabel required w-100 p-1" for="maritalStatus">Marital Status</label>
                                <select class="form-select text-uppercase w-100 personalSelectBox" name="mStatus" id="maritalStatus" required>
                                    <option value="SINGLE">Single</option>
                                    <option value="MARRIED">Married</option>
                                    <option value="LIVE IN">Live-in</option>
                                    <option value="SEPARATED">Separated</option>
                                    <option value="ANNULED">Annuled</option>
                                    <option value="WIDOWED">Widowed</option>
                                </select>
                            </div>
                            <div class="col col-lg-2 col-md-3 col-sm-6 col-12">
                                <label class="col-form-label fieldLabel required w-100 p-1 p-1" for="religionSelect">Religion</label>
                                <select class="form-select text-uppercase w-100 personalSelectBox" name="religion" id="religionSelect" required>
                                    <option value="ATHEIST">Atheist</option>
                                    <option value="BUDDHIST">Buddhist</option>
                                    <option value="CHRISTIAN">Christian</option>
                                    <option value="MUSLIM">Muslim</option>
                                    <option value="OTHERS">Others</option>
                                </select>
                            </div>
                            <div class="col col-lg-2 col-md-3 col-sm-6 col-12">
                                <div class="disabilityDiv">
                                    <label class="col-form-label fieldLabel required w-100 p-1" for="disabilitySelect">Disability</label>
                                    <!-- Class form-select  removed from select element in Disability -->
                                    <select class="selectpicker form-control text-uppercase houseSelectBox" multiple name="disability" data-selected-text-format="count > 3" id="disabilitySelect" required>
                                        <option value="NONE" selected>None</option>
                                        <option value="COMMUNICATION DISABILITY">Communication disability</option>
                                        <option value="LEARNING DISABILITY">Learning disability</option>
                                        <option value="MENTAL DISABILITY">Mental disability</option>
                                        <option value="ORTHOPEDIC DISABILITY">Orthopedic disability</option>
                                        <option value="PSYCHOSOCIAL DISABILITY">Psychosocial disability</option>
                                        <option value="VISION DISABILITY">Vision disability</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col col-lg-2 col-md-3 col-sm-6 col-12">
                                <label class="col-form-label fieldLabel required w-100 p-1" for="residentVote">Voter Type</label>
                                <select class="form-select text-uppercase w-100 personalSelectBox" name="voterType" id="residentVote" required>
                                    <option value="REGISTERED">Registered</option>
                                    <option value="UNREGISTERED">Unregistered</option>
                                </select>
                            </div>

                            <div class="col col-lg-2 col-md-3 col-sm-6 col-12">
                                <label class="col-form-label fieldLabel required w-100 p-1" for="contactNo">Contact No.</label>
                                <input class="form-control userInput text-uppercase w-100 phoneNumber" type="text" name="contact" id="contactNo" pattern="[0-9]{11}" title="Contact number should only contain 11 characters from 0-9" placeholder="09XXXXXXXXX" inputmode="numeric" required>
                            </div>

                            <div class="col col-lg-3 col-md-3 col-sm-8 col-12">
                                <label class="col-form-label fieldLabel required w-100 p-1" for="residentAddress">House No./Street/Subdivision</label>
                                <input class="form-control userInput text-uppercase w-100" type="text" name="address" id="residentAddress" required>
                            </div>
                            <div class="col col-lg-2 col-md-3 col-sm-4 col-12">
                                <label class="col-form-label fieldLabel required w-100 p-1" for="residentPurok">Purok</label>
                                <select class="form-select text-uppercase w-100 personalSelectBox" placeholder="Purok" name="purok" id="residentPurok" required>
                                    <option value="CARDINAL">Cardinal</option>
                                    <option value="CORDILLERA">Cordillera</option>
                                    <option value="DOÑA PETRA">Doña Petra</option>
                                    <option value="DOÑA REGINA 1">Doña Regina 1</option>
                                    <option value="DOÑA REGINA 2">Doña Regina 2</option>
                                    <option value="DOÑA REGINA 3">Doña Regina 3</option>
                                    <option value="FAMILY VILLAGE">Family Village</option>
                                    <option value="IRAQ">Iraq</option>
                                    <option value="LOOBAN">Looban</option>
                                    <option value="MANGGAHAN">Manggahan</option>
                                    <option value="NAYON">Nayon</option>
                                    <option value="ORMOC">Ormoc</option>
                                    <option value="PULONG KENDI">Pulong Kendi</option>
                                    <option value="PUTING KRUS">Puting Krus</option>
                                    <option value="SAINT ANTHONY">Saint Anthony </option>
                                    <option value="SAMPAGUITA ST.">Sampaguita St.</option>
                                    <option value="SMOKEY MOUNTAIN">Smokey Mountain </option>
                                </select>
                            </div>
                            <div class="col col-lg-3 col-md-3 col-sm-8 col-12">
                                <label class="col-form-label fieldLabel w-100 p-1" for="org" id="organizationLbl">Organization (if any)</label>
                                <input class="form-control userInput text-uppercase w-100" type="text" name="organization" id="org">
                            </div>
                            <div class="col col-lg-2 col-md-3 col-sm-4 col-12">
                                <label class="col-form-label fieldLabel w-100 p-1" for="remarks" id="remarksLbl">Remarks (if any)</label>
                                <select class="selectpicker form-control text-uppercase personalSelectBox" multiple name="remarks" placeholder="Select Remarks" data-selected-text-format="count > 3" id="remarkDrop">
                                    <option value="PUROK LEADER">PUROK LEADER</option>
                                    <option value="SK SCHOLAR">SK SCHOLAR</option>
                                    <option value="SOLO PARENT">SOLO PARENT</option>
                                    <option value="TEENAGE PREGNANCY">TEENAGE PREGNANCY</option>
                                </select>
                            </div>
                        </div>
                        <!-- Personal Info card end -->
                    </div>

                    <div class="row gy-2 my-2 mx-0">
                        <!-- Educational Info -->
                        <div class="col eStatus col-lg-6 col-md-12 col-sm-12 col-12 p-0">
                            <div class="card p-3 darkBG" id="educCard">
                                <div class="d-flex align-items-center markerDiv rounded-2 m-0 w-100" id="markerEduc">
                                    <label class="d-flex align-items-center justify-content-start">
                                        <input type="radio" id="educCheck" class="statusCheck" name="educInfo" checked>
                                        Educational Status
                                    </label>
                                </div>
                                <div class="row m-0 my-3 gy-3 gx-3">
                                    <div class="col col-lg-4 col-md-4 col-sm-6 col-12 colHolder">
                                        <label class="col-form-label educFieldLabel required w-100 p-1" id="educStatusLbl" for="selectEducStatus">Educational Status</label>
                                        <select class="form-select educSelectBox text-uppercase w-100" name="educStatus" id="selectEducStatus" required>
                                            <option value="ENROLLED">Enrolled</option>
                                            <option value="OUT-OF-SCHOOL YOUTH">Out-of-school Youth</option>
                                            <option value="WORKING STUDENT">Working Student</option>
                                        </select>
                                    </div>
                                    <div class="col col-lg-4 col-md-4 col-sm-6 col-12 colHolder">
                                        <label class="col-form-label educFieldLabel required w-100 p-1" id="levelLbl" for="selectLevel">Level</label>
                                        <select class="form-select educSelectBox text-uppercase w-100" name="educLevel" id="selectLevel" required>
                                            <option value="PRE-ELEMENTARY">Pre-elementary</option>
                                            <option value="ELEMENTARY">Elementary</option>
                                            <option value="JUNIOR HIGH SCHOOL">Junior High School</option>
                                            <option value="SENIOR HIGH SCHOOL">Senior High School</option>
                                            <option value="ALTERNATIVE LEARNING SYSTEM">Alternative Learning System (ALS)</option>
                                            <option value="DIPLOMA COURSE">Diploma Course</option>
                                            <option value="COLLEGE">College</option>
                                            <option value="GRADUATE STUDIES">Graduate Studies</option>
                                        </select>
                                    </div>
                                    <div class="col col-lg-4 col-md-4 col-sm-4 col-12 colHolder">
                                        <label class="col-form-label educFieldLabel required w-100 p-1" id="schoolTypeLbl" for="selectSchoolType">School Type</label>
                                        <select class="form-select educSelectBox text-uppercase w-100" name="schoolType" id="selectSchoolType" required>
                                            <option value="PRIVATE">Private</option>
                                            <option value="PUBLIC">Public</option>s
                                        </select>
                                    </div>
                                    <div class="col col-lg-12 col-md-12 col-sm-8 col-12 colHolder">
                                        <label class="col-form-label educFieldLabel required w-100 p-1" id="schoolLbl" for="inputSchool">Name of School</label>
                                        <input class="form-control educUserInput text-uppercase w-100" type="text" name="schoolName" id="inputSchool" required>
                                    </div>
                                    <div class="col col-lg-6 col-md-6 col-sm-12 col-12 colHolder">
                                        <label class="col-form-label educFieldLabel required w-100 p-1" id="educIndustryLbl" for="selectEducIndustry">Work Industry</label>
                                        <select class="form-select text-uppercase w-100 educSelectBox" name="educIndustry" id="selectEducIndustry" required>
                                            <option value="1">Accounting</option>
                                            <option value="2">Advertising and Marketing</option>
                                            <option value="3">Aerospace</option>
                                            <option value="4">Agriculture</option>
                                            <option value="5">Animation</option>
                                            <option value="6">Apparel and Fashion</option>
                                            <option value="7">Architecture and Planning</option>
                                            <option value="8">Arts and Crafts</option>
                                            <option value="9">Automobile Engineering</option>
                                            <option value="10">Auto Mechanics</option>
                                            <option value="11">Aviation</option>
                                            <option value="12">BPO</option>
                                            <option value="13">Buy and Sell</option>
                                            <option value="14">Casinos and Gambling</option>
                                            <option value="15">Chemical</option>
                                            <option value="16">Computer Technology</option>
                                            <option value="17">Construction</option>
                                            <option value="18">Cosmetics</option>
                                            <option value="19">Design</option>
                                            <option value="20">E-commerce</option>
                                            <option value="21">Education</option>
                                            <option value="22">Electrical Power</option>
                                            <option value="23">Electronics</option>
                                            <option value="24">Event Services</option>
                                            <option value="25">Finance and Insurance Services</option>
                                            <option value="26">Fishery</option>
                                            <option value="27">Fitness and Wellness</option>
                                            <option value="28">Furniture</option>
                                            <option value="29">Food and Beverages</option>
                                            <option value="30">Government</option>
                                            <option value="31">Graphic Arts</option>
                                            <option value="32">Healthcare and Welfare</option>
                                            <option value="33">Hospitality Management</option>
                                            <option value="34">Law Enforcement</option>
                                            <option value="35">Legislative Office</option>
                                            <option value="36">Luxury Goods and Jewelry</option>
                                            <option value="37">Manufacturing</option>
                                            <option value="38">Maritime</option>
                                            <option value="39">Military</option>
                                            <option value="40">Mining</option>
                                            <option value="41">Packaging and Containers</option>
                                            <option value="42">Performing Arts</option>
                                            <option value="43">Personal Service</option>
                                            <option value="44">Pharmaceutical</option>
                                            <option value="45">Poultry</option>
                                            <option value="46">Printing and Publishing</option>
                                            <option value="47">Production</option>
                                            <option value="48">Public Safety</option>
                                            <option value="49">Photography and Film</option>
                                            <option value="50">Quick Service Restaurant (QSR)</option>
                                            <option value="51">Real Estate</option>
                                            <option value="52">Recreational Facilities and Services</option>
                                            <option value="53">Religious Institutions</option>
                                            <option value="54">Retail Industry</option>
                                            <option value="55">Shipping Services</option>
                                            <option value="56">Staffing and Recruiting</option>
                                            <option value="57">Supply Chain Management</option>
                                            <option value="58">Telecommunications</option>
                                            <option value="59">Transportation</option>
                                            <option value="60">Tourism</option>
                                            <option value="61">Utilities</option>
                                            <option value="62">Veterinary</option>
                                            <option value="63">Warehousing</option>
                                            <option value="64">Others</option>
                                        </select>
                                    </div>
                                    <div class="col col-lg-6 col-md-6 col-sm-12 col-12 colHolder">
                                        <label class="col-form-label educFieldLabel required w-100 p-1" id="educSalaryLbl" for="selectEducSalary">Salary Range</label>
                                        <select class="form-select educSelectBox text-uppercase w-100" name="educSalary" id="selectEducSalary" required>
                                            <option value="1">Less than 10,000</option>
                                            <option value="2">10,000-20,999</option>
                                            <option value="3">21,000-30,999</option>
                                            <option value="4">31,000-40,999</option>
                                            <option value="5">41,000-50,999</option>
                                            <option value="6">More than 50,999</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Employment Info -->
                        <div class="col eStatus col-lg-6 col-md-12 col-sm-12 col-12 p-0">
                            <div class="card p-3 darkBG" id="employCard">
                                <div class="d-flex align-items-center markerDiv rounded-2 m-0 w-100" id="markerEmploy">
                                    <label class="d-flex align-items-center">
                                        <input type="radio" id="employCheck" class="statusCheck" name="employInfo">
                                        Employment Status
                                    </label>
                                </div>
                                <div class="row m-0 my-3 gy-3 gx-3">
                                    <div class="col col-lg-4 col-md-4 col-sm-6 col-12 colHolder">
                                        <label class="col-form-label employFieldLabel required w-100 p-1" id="employStatusLbl" for="selectEmployStatus">Employee Status</label>
                                        <select class="form-select employSelectBox text-uppercase w-100" name="employeeStatus" id="selectEmployStatus" required>
                                            <option value="EMPLOYED">Employed</option>
                                            <option value="UNEMPLOYED">Unemployed</option>
                                        </select>
                                    </div>
                                    <div class="col col-lg-4 col-md-4 col-sm-6 col-12 colHolder">
                                        <label class="col-form-label employFieldLabel required w-100 p-1" id="employTypeLbl" for="selectEmployeeType">Employee Type</label>
                                        <select class="form-select employSelectBox text-uppercase w-100" name="employeeType" id="selectEmployeeType" required>
                                            <option value="REGULAR">Regular</option>
                                            <option value="CONTRACTUAL">Contractual</option>
                                            <option value="JOB ORDER">Job Order</option>
                                        </select>
                                    </div>
                                    <div class="col col-lg-4 col-md-4 col-sm-4 col-12 colHolder">
                                        <label class="col-form-label employFieldLabel required w-100 p-1" id="employerTypeLbl" for="selectEmployerType">Employer Type</label>
                                        <select class="form-select employSelectBox text-uppercase w-100" name="employerType" id="selectEmployerType" required>
                                            <option value="PRIVATE">Private</option>
                                            <option value="PUBLIC">Public</option>
                                        </select>
                                    </div>
                                    <div class="col col-lg-12 col-md-12 col-sm-8 col-12 colHolder">
                                        <label class="col-form-label employFieldLabel required w-100 p-1" id="employerLbl" for="inputEmployer">Name of Employer/Company/Business</label>
                                        <input class="form-control employUserInput text-uppercase w-100" type="text" name="employerName" id="inputEmployer" required>
                                    </div>
                                    <div class="col col-lg-6 col-md-6 col-sm-12 col-12 colHolder">
                                        <label class="col-form-label employFieldLabel required w-100 p-1" id="employIndustryLbl" for="selectEmployIndustry">Work Industry</label>
                                        <select class="form-select text-uppercase w-100 employSelectBox" name="employIndustry" id="selectEmployIndustry" required>
                                            <option value="1">Accounting</option>
                                            <option value="2">Advertising and Marketing</option>
                                            <option value="3">Aerospace</option>
                                            <option value="4">Agriculture</option>
                                            <option value="5">Animation</option>
                                            <option value="6">Apparel and Fashion</option>
                                            <option value="7">Architecture and Planning</option>
                                            <option value="8">Arts and Crafts</option>
                                            <option value="9">Automobile Engineering</option>
                                            <option value="10">Auto Mechanics</option>
                                            <option value="11">Aviation</option>
                                            <option value="12">BPO</option>
                                            <option value="13">Buy and Sell</option>
                                            <option value="14">Casinos and Gambling</option>
                                            <option value="15">Chemical</option>
                                            <option value="16">Computer Technology</option>
                                            <option value="17">Construction</option>
                                            <option value="18">Cosmetics</option>
                                            <option value="19">Design</option>
                                            <option value="20">E-commerce</option>
                                            <option value="21">Education</option>
                                            <option value="22">Electrical Power</option>
                                            <option value="23">Electronics</option>
                                            <option value="24">Event Services</option>
                                            <option value="25">Finance and Insurance Services</option>
                                            <option value="26">Fishery</option>
                                            <option value="27">Fitness and Wellness</option>
                                            <option value="28">Furniture</option>
                                            <option value="29">Food and Beverages</option>
                                            <option value="30">Government</option>
                                            <option value="31">Graphic Arts</option>
                                            <option value="32">Healthcare and Welfare</option>
                                            <option value="33">Hospitality Management</option>
                                            <option value="34">Law Enforcement</option>
                                            <option value="35">Legislative Office</option>
                                            <option value="36">Luxury Goods and Jewelry</option>
                                            <option value="37">Manufacturing</option>
                                            <option value="38">Maritime</option>
                                            <option value="39">Military</option>
                                            <option value="40">Mining</option>
                                            <option value="41">Packaging and Containers</option>
                                            <option value="42">Performing Arts</option>
                                            <option value="43">Personal Service</option>
                                            <option value="44">Pharmaceutical</option>
                                            <option value="45">Poultry</option>
                                            <option value="46">Printing and Publishing</option>
                                            <option value="47">Production</option>
                                            <option value="48">Public Safety</option>
                                            <option value="49">Photography and Film</option>
                                            <option value="50">Quick Service Restaurant (QSR)</option>
                                            <option value="51">Real Estate</option>
                                            <option value="52">Recreational Facilities and Services</option>
                                            <option value="53">Religious Institutions</option>
                                            <option value="54">Retail Industry</option>
                                            <option value="55">Shipping Services</option>
                                            <option value="56">Staffing and Recruiting</option>
                                            <option value="57">Supply Chain Management</option>
                                            <option value="58">Telecommunications</option>
                                            <option value="59">Transportation</option>
                                            <option value="60">Tourism</option>
                                            <option value="61">Utilities</option>
                                            <option value="62">Veterinary</option>
                                            <option value="63">Warehousing</option>
                                            <option value="64">Others</option>
                                        </select>
                                    </div>
                                    <div class="col col-lg-6 col-md-6 col-sm-12 col-12 colHolder">
                                        <label class="col-form-label employFieldLabel required w-100 p-1" id="employSalaryLbl" for="selectEmploySalary">Salary Range</label>
                                        <select class="form-select employSelectBox text-uppercase w-100" name="employSalary" id="selectEmploySalary" required>
                                            <option value="1">Less than 10,000</option>
                                            <option value="2">10,000-20,999</option>
                                            <option value="3">21,000-30,999</option>
                                            <option value="4">31,000-40,999</option>
                                            <option value="5">41,000-50,999</option>
                                            <option value="6">More than 50,999</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- House Declaration -->
                    <div class="card p-3 my-4 darkBG">
                        <div class="row m-0">
                            <div class="col col-12 p-0">
                                <div class="d-flex align-items-center markerDiv rounded-2">
                                    House Declaration
                                </div>
                            </div>
                        </div>
                        <div class="row m-0 my-3 gy-3 gx-3" id="householdRow">
                            <div class="col col-lg-3 col-md-4 col-sm-12 col-12">
                                <label class="col-form-label fieldLabel required w-100 p-1" for="headFname">First Name</label>
                                <input class="form-control userInput text-uppercase w-100" type="text" name="hFname" id="headFname" required>
                            </div>
                            <div class="col col-lg-3 col-md-4 col-sm-12 col-12">
                                <label class="col-form-label fieldLabel required w-100 p-1" id="hMNameLbl" for="headMname">Middle Name</label>
                                <input class="form-control userInput text-uppercase w-100" type="text" name="hMname" id="headMname">
                            </div>
                            <div class="col col-lg-3 col-md-4 col-sm-12 col-12">
                                <label class="col-form-label fieldLabel required w-100 p-1" for="headLname">Last Name</label>
                                <input class="form-control userInput text-uppercase w-100" type="text" name="hLname" id="headLname">
                            </div>
                            <div class="col col-lg-1 col-sm-6 col-12">
                                <label class="col-form-label fieldLabel required w-100 p-1" for="headSuffix" id="headSuffixLbl">Suffix</label>
                                <input class="form-control userInput text-uppercase w-100" type="text" name="hSuffix" id="headSuffix">
                            </div>
                            <div class="col-lg-2 col-sm-6 col-12">
                                <label class="col-form-label fieldLabel required w-100 p-1" for="famCount">No. of Family Members</label>
                                <select class="form-select text-uppercase w-100 houseSelectBox" name="membersCount" id="famCount" required>
                                    <option value="LESS THAN 5">Less than 5</option>
                                    <option value="5 to 10">5 to 10</option>
                                    <option value="11 to 15">11 to 15</option>
                                    <option value="16 to 20">16 to 20</option>
                                    <option value="MORE THAN 20">More than 20</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Button -->
                    <div class="row m-0 d-flex d-md-flex d-lg-flex d-xxl-flex justify-content-md-end align-items-md-center justify-content-lg-end align-items-lg-center justify-content-xxl-end gx-2" id="rowBtn">
                        <div class="col d-flex d-sm-flex d-md-flex d-xxl-flex justify-content-center justify-content-sm-center justify-content-md-end justify-content-xxl-end col-lg-2 col-md-4 col-sm-12 col-12 p-0">
                            <button class="btn controlBtn" name="submitBtn" type="submit" form="addForm">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap-select -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

    <!-- Custom Script -->
    <script type="text/javascript" src="assets/js/addEntry.js"></script>
    <script type="text/javascript" src="assets/js/sideMenu.js"></script>
    <script type="text/javascript" src="assets/js/settings.js"></script>
    <script type="text/javascript" src="assets/js/phoneNum.js"></script>

    <!-- Active Link -->
    <script type="text/javascript">
        $(document).ready(function() {
            $("#add-link").addClass('nav-active');
            $("#add-md-link").addClass("nav-md-active");
        })
    </script>
</body>

</html>