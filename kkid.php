<?php require 'connection.php' ?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>KK ID Card</title>

    <!-- Custom Stylesheets -->
    <link rel="stylesheet" href="assets/scss/kkid.css">
    <link rel="stylesheet" href="assets/scss/idtcpdf.css">
    <link rel="stylesheet" href="assets/scss/mediaquery.css">
    <link rel="stylesheet" href="assets/scss/sideMenu.css">

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
                    <i class="fa-solid fa-bars me-4 d-none" id="hamburger" role="button"></i>
                    <i class="fa-solid fa-xmark me-4" id="close" role="button"></i>
                    <span class="pageTitle">KK ID Card</span>
                </div>

                <div class="col-md-4 d-none d-md-flex justify-content-end align-items-center">
                    <span class="accountType">Super Admin Account</span>
                </div>
            </div>
            <hr id="headerHR">
        </header>

        <!-- BODY START -->
        <div class="container-fluid content">
            <div class="sectionDiv">
                <!-- Scan Button -->
                <div class="row d-flex justify-content-end align-ittems-center mx-0 mt-1 mb-3">
                    <button class="btn rounded-2 px-3 py-2 w-auto d-flex justify-content-center align-items-center" id="scanBtn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" class="m-0 p-0" viewBox="0 0 16 16" id="scanIcon">
                            <path d="M0 .5A.5.5 0 0 1 .5 0h3a.5.5 0 0 1 0 1H1v2.5a.5.5 0 0 1-1 0v-3Zm12 0a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0V1h-2.5a.5.5 0 0 1-.5-.5ZM.5 12a.5.5 0 0 1 .5.5V15h2.5a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5v-3a.5.5 0 0 1 .5-.5Zm15 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1 0-1H15v-2.5a.5.5 0 0 1 .5-.5ZM4 4h1v1H4V4Z" />
                            <path d="M7 2H2v5h5V2ZM3 3h3v3H3V3Zm2 8H4v1h1v-1Z" />
                            <path d="M7 9H2v5h5V9Zm-4 1h3v3H3v-3Zm8-6h1v1h-1V4Z" />
                            <path d="M9 2h5v5H9V2Zm1 1v3h3V3h-3ZM8 8v2h1v1H8v1h2v-2h1v2h1v-1h2v-1h-3V8H8Zm2 2H9V9h1v1Zm4 2h-1v1h-2v1h3v-2Zm-4 2v-1H8v1h2Z" />
                            <path d="M12 9h2V8h-2v1Z" />
                        </svg>
                        <span id="scanBtnTxt">Scan QR Code</span>
                    </button>
                </div>

                <!-- Contents -->
                <div class="m-0">
                    <div class="row kkidBody p-0 m-0 gy-5" id="kkidPage">
                        <!-- Batch Print -->
                        <div class="col-lg-6 col-md-12 col-sm-12 col-12 p-0 m-0">
                            <h5>Batch Printing</h5>
                            <form class="m-0 mt-4 d-flex justify-content-center align-items-center" id="kkidForm">
                                <div class="categoryCol row d-flex justify-content-center align-items-center my-auto gy-3 w-100">
                                    <!-- Category: Age -->
                                    <div class="col-sm-3 col-12 p-0">
                                        <div class="form-check categoryDiv ">
                                            <input class="form-check-input checkboxCategory" type="checkbox" id="checkAge">
                                            <label class="form-check-label categoryLbl" for="checkAge">Age</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-9 col-12 p-0">
                                        <select class="form-select categoryOption" name="age" id="selectAge" disabled>
                                            <option value="children" selected>0-14 years old (Children)</option>
                                            <option value="kabataan">15-30 years old (Kabataan)</option>
                                            <option value="genResidents">31-59 years old (General Residents)</option>
                                            <option value="seniorCitizens">60 years old and above (Senior Citizens)</option>
                                        </select>
                                    </div>
                                    <!-- Category: Purok -->
                                    <div class="col-sm-3 col-12 p-0">
                                        <div class="form-check categoryDiv">
                                            <input class="form-check-input checkboxCategory" type="checkbox" id="checkPurok">
                                            <label class="form-check-label categoryLbl" for="checkPurok">Purok</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-9 col-12 p-0">
                                        <form action="" method="POST">
                                            <select class="form-select categoryOption" name="purok" id="selectPurok" disabled>
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
                                                <option value="SAMPAGUITA ST.">IraSampaguita St.</option>
                                                <option value="SMOKEY MOUNTAIN">Smokey Mountain </option>
                                            </select>
                                        </form>
                                    </div>
                                    <!-- Custom Category -->
                                    <div class="col-12 p-0">
                                        <div class="form-check categoryDiv">
                                            <input class="form-check-input checkboxCategory" type="checkbox" id="checkCustom">
                                            <label class="form-check-label categoryLbl" for="checkCustom">Custom</label>
                                        </div>
                                    </div>
                                    <!-- Custom Form -->
                                    <div class="customDiv mt-2 mb-3 d-flex justify-content-center">
                                        <div class="row customCategory gy-4">
                                            <div class="col-md-3 col-sm-12 col-12 p-0">
                                                <label class="customLbl col-form-label" for="fName">First Name</label>
                                            </div>
                                            <div class="col col-lg-9 col-md-9 col-sm-12 col-12 p-0">
                                                <input class="form-control userInput text-uppercase w-100" type="text" name="firstName" id="fName" required disabled>
                                            </div>

                                            <div class="col col-lg-3 col-md-3 col-sm-12 col-12 p-0">
                                                <label class="customLbl col-form-label" for="mName">Middle Name</label>
                                            </div>
                                            <div class="col col-lg-9 col-md-9 col-sm-12 col-12 p-0">
                                                <input class="form-control userInput text-uppercase w-100" type="text" name="middleName" id="mName" required disabled>
                                            </div>

                                            <div class="col col-lg-3 col-md-3 col-sm-12 col-12 p-0">
                                                <label class="customLbl col-form-label" for="lName">Last Name</label>
                                            </div>
                                            <div class="col col-lg-9 col-md-9 col-sm-12 col-12 p-0">
                                                <input class="form-control userInput text-uppercase w-100" type="text" name="lastName" id="lName" required disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <div class="row mx-0">
                                <?php
                                if (isset($_POST['submit'])) {
                                    $fName  = $_POST['firstName'];
                                    $mName  = isset($_POST['age']) ? $_POST['middleName'] : "";
                                    $lName  = $_POST['lastName'];
                                    $age = isset($_POST['age']) ? $_POST['age'] : "";
                                    $purok = isset($_POST['purok']) ? $_POST['purok'] : "";

                                    //FOR ENCRYPTED IDs
                                    $customEncrypt = $fName . " " . $mName . " " . $lName;

                                    $ciphering = "AES-128-CTR"; //cipher method

                                    $iv_length = openssl_cipher_iv_length($ciphering); //Using OpenSSl Encryption method
                                    $options = 0;

                                    $encryption_iv = '1234567891011121'; // Non-NULL Initialization Vector for encryption   
                                    $encryption_key = "KKIS2022-2023"; // Storing the encryption key

                                    // Using openssl_encrypt() function to encrypt the data
                                    $encryptedName = openssl_encrypt($customEncrypt, $ciphering, $encryption_key, $options, $encryption_iv);

                                    //Children (ADD WHERE CLAUSE)
                                    $children = "SELECT CONCAT(`last_name`, ', ', `first_name`) AS `full_name`, `birthday`,`purok`, `qr_code` FROM `residents` WHERE DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), birthday)), '%Y') + 0 < 15";

                                    //Kabatan (ADD WHERE CLAUSE)
                                    $kabataan = "SELECT CONCAT(`last_name`, ', ', `first_name`) AS `full_name`, `birthday`,`purok`, `qr_code` FROM `residents` WHERE DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), birthday)), '%Y') + 0 BETWEEN 14 AND 31";

                                    //Gen Residents (ADD WHERE CLAUSE)
                                    $genResidents = "SELECT CONCAT(`last_name`, ', ', `first_name`) AS `full_name`, `birthday`,`purok`, `qr_code` FROM `residents` WHERE DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), birthday)), '%Y') + 0 BETWEEN 30 AND 60";

                                    //Senior Citizens (ADD WHERE CLAUSE)
                                    $seniorCitizens = "SELECT CONCAT(`last_name`, ', ', `first_name`) AS `full_name`, `birthday`,`purok`, `qr_code` FROM `residents` WHERE DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), birthday)), '%Y') + 0 > 59";

                                    //Custom
                                    $custom = "SELECT CONCAT(`first_name`, ' ', `middle_name`, ' ', `last_name`) AS `full_name`, `birthday`,`purok`, `qr_code` FROM `residents` WHERE `rencrypted_id` = $encryptedName";


                                    // if ((isset($_POST['age']) && isset($_POST['purok'])) || (isset($_POST['age']) && isset($_POST['purok']))) {
                                    // }
                                }
                                ?>
                            </div>
                        </div>

                        <!-- Preview -->
                        <div class="col idPreview col-lg-6 col-12 p-0 m-0">
                            <div class="row m-0 w-100">
                                <div class="col col-12 p-0">
                                    <h5 class="text-start mb-3">ID Preview</h5>
                                </div>
                                <div class="col text-center col-12 p-3 pt-1 w-100">
                                    <img class="img-fluid previewContainer" src="assets/img/misc/ID.png">
                                </div>
                            </div>
                        </div>

                        <!-- Control buttons -->
                        <div class="row d-flex justify-content-lg-start justify-content-sm-center m-0">
                            <div class="col-lg-3 col-md-3 col-sm-6 col-6 d-flex justify-content-center">
                                <button class="btn controlBtn" id="clearBtn" type="button" form="kkidForm" disabled>Clear All</button>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-6 d-flex justify-content-center">
                                <button class="btn controlBtn" id="submitBtn" type="button" form="kkidForm" disabled>Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Script -->
    <script src="assets/js/kkid.js"></script>
    <script type="text/javascript" src="assets/js/sideMenu.js"></script>

    <!-- Active Link -->
    <script type="text/javascript">
        $(document).ready(function() {
            $("#kkid-link").addClass('nav-active');
            $("#kkid-md-link").addClass("nav-md-active");
        })
    </script>
</body>

</html>