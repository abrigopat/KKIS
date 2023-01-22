<?php
require "controller.php";
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Log in/Sign Up</title>

    <!-- Fundamental Links -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=DM+Sans:400,500,700&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins&amp;display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />

    <!-- Custom Stylesheets -->
    <link rel="stylesheet" href="assets/css/animatedBG.css">
    <link rel="stylesheet" href="assets/scss/index.css">
    <link rel="stylesheet" href="assets/scss/sideMenu.css">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/logos/kkis.ico">
</head>

<body>

    <div class="header">
        <!--Content before waves-->
        <div class="inner-header flex">

            <div id="index-wrapper">
                <div class="container-fluid d-flex justify-content-center align-items-center indexContent">
                    <!-- Log in -->
                    <div class="card d-flex justify-content-center mt-3" id="loginCard">
                        <div class="card-body">
                            <div class="row m-0 w-100">
                                <div class="col-12 d-flex justify-content-center">
                                    <img src="assets/img/logos/kkis-logo.png" alt="KKIS-Logo">
                                </div>
                            </div>
                            <div class="row m-0 d-flex justify-content-center">
                                <div class="col-12 text-center">
                                    <span class="cardHead">Welcome!</span>
                                </div>
                            </div>
                            <div class="row mx-3 my-0">
                                <div class="col-12 text-center p-0"><img class="img-fluid hrSvg" src="assets/img/misc/hr.svg"></div>
                            </div>
                            <!-- Form -->
                            <form id="loginForm" action="" method="post">
                                <!-- Fields -->
                                <div class="row gx-3 gy-3 fieldRow">
                                    <div class="col-12">
                                        <input class="form-control" type="email" name="email" placeholder="Email" required>
                                    </div>
                                    <!-- BASE PW FIELD W/ EYE ICON -->
                                    <div class="col-12">
                                        <input class="form-control" type="password" name="password" required="" placeholder="Password" id="id_password">
                                        <i class="toggle-password far fa-eye-slash" id="togglePassword" toggle="#password-field"></i>
                                        </span>
                                    </div>

                                    <!-- TO SHOW PW - CHECKBOX
                                    <div class="col-12">
                                        <input type="checkbox" onclick="showPassword();" name="showPass" placeholder="Show Password" id="showPass" required>
                                        Show Password
                                    </div>
                                    -->

                                    <!-- FORGOT PW -->
                                    <div class="col-12 mt-2 d-flex justify-content-center">
                                        <a href="#" id="forgotPassText">Forgot Password?</a>
                                    </div>
                                </div>
                                <!-- Log in btn -->
                                <div class="row m-0 mt-3" id="rowBtn">
                                    <div class="col-12 d-flex justify-content-center align-items-center">
                                        <button class="btn controlBtn rounded-pill mt-3" name="loginBtn" type="submit" form="loginForm" id="loginBtn">Log in</button>
                                    </div>
                                    <div class="col-12 d-flex justify-content-center align-items-center">
                                        <p class="mt-2 text-center" id="signup-phrase">Don't have an account yet? <a href="#" class="signup-txt" id="signupLink">Sign up</a></p>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Sign Up -->
                    <div class="card d-flex justify-content-center d-none" id="signupCard">
                        <div class="card-body">
                            <div class="row m-0 w-100">
                                <div class="col-12 d-flex justify-content-center">
                                    <img src="assets/img/logos/kkis-logo.png" alt="KKIS-Logo">
                                </div>
                            </div>
                            <div class="row m-0 d-flex justify-content-center">
                                <div class="col-12 text-center">
                                    <span class="cardHead">Sign Up</span>
                                </div>
                            </div>
                            <div class="row mx-3 my-0">
                                <div class="col-12 text-center p-0"><img class="img-fluid hrSvg" src="assets/img/misc/hr.svg"></div>
                            </div>
                            <!-- Form -->
                            <form id="signupForm" action="" onSubmit="return validate();" method="POST">
                                <!-- Fields -->
                                <div class="row gx-3 gy-3 fieldRow">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                        <input class="form-control text-capitalize" type="text" name="firstName" placeholder="First Name" required>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                        <input class="form-control text-capitalize" type="text" name="lastName" placeholder="Last Name" required>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                        <input class="form-control" type="date" name="birthday" placeholder="Birthday" required>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                        <input class="form-control" type="text" name="contactNumber" pattern="[0-9]{11}" title="Contact number should only contain 11 characters from 0-9" inputmode="numeric" placeholder="Contact No." required>
                                    
                                    </div>
                                    <div class="col-12">
                                        <input class="form-control" type="email" name="email" placeholder="Email" maxlength="30" required>
                                    </div>
                                    <div class="col-12">
                                        <input class="form-control" type="password" name="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number, one uppercase and lowercase letter, and should be at least 8 or more 
                                        characters" placeholder="Password" id="signup_password" required>
                                        <i class="toggle-password far fa-eye-slash" id="toggleSignupPassword" toggle="#password-field"></i>
                                        </span>
                                    </div>
                                    <div class="col-12">
                                        <input class="form-control" type="password" name="confirmPassword" placeholder="Confirm Password" id="signup-confirm_password" maxlength="30" required>
                                        <i class="toggle-password far fa-eye-slash" id="toggleSignupconPassword" toggle="#password-field"></i>
                                        </span>
                                    </div>
                                    <!-- Password mismatch -->
                                    <div id="messageDiv" class="d-none d-flex justify-content-start align-items-center my-0 py-1">
                                        <i class="fa-solid fa-circle-exclamation" style="color: red; margin-right: 5px"></i>
                                        <span id="message" class=" text-start py-1 my-0"></span>
                                    </div>
                                </div>
                                <!-- Sign up btn -->
                                <div class="row m-0" id="rowBtn">
                                    <div class="d-flex justify-content-center align-items-center px-0 py-3">
                                        <input type="checkbox" name="agreeCheck" id="agreeCheck" class="me-2" required>
                                        <label id="agree-phrase" for="agreeCheck">I have read and agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsConditions">Terms and Conditions</a></label>
                                    </div>
                                    <div class="col-12 d-flex justify-content-center align-items-center">
                                        <button class="btn controlBtn rounded-pill mt-3" name="signupBtn" type="submit" form="signupForm" id="signupBtn">Sign up</button>
                                    </div>
                                    <div class="col-12 d-flex justify-content-center align-items-center">
                                        <p class="mt-2 text-center" id="login-phrase">Already have an account? <a href="#" class="login-txt" id="loginLink">Log in</a></p>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="modal fade" tabindex="-1" id="termsConditions">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <div class="d-flex justify-content-start align-items-start">
                                        <img src="assets/img/logos/kkis-brand.svg" alt="" width="50px" height="auto">
                                        <span class="ms-1 modal-title" id="modalTitle">KKIS</span>
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <h5 class="text-start">Terms and Conditions</h5>
                                    <p class="text-start">
                                        These Website Standard Terms and Conditions written on this webpage shall manage your use of our website, Katipunan ng Kabataan Information System accessible at url.
                                    </p>

                                    <p class="text-start">
                                        These Terms will be applied fully and affect your use of this Website. By using this Website, you agreed to accept all terms and conditions written here. You must not use this Website if you disagree with any of these Website\'s Standard Terms and Conditions.
                                    </p>

                                    <p class="text-start">Only authorized personnel are allowed to use this Website.</p>

                                    <br>
                                    <h5 class="text-start">Interpretation and Definitions</h5>

                                    <h6 class="text-start">Interpretation</h6>
                                    <p class="text-start">
                                        The words of which the initial letter is capitalized have meanings defined under the following conditions. The following definitions shall have the same meaning regardless of whether they appear in singular or plural.
                                    </p>

                                    <h6 class="text-start">Definitions</h6>
                                    <p class="text-start">For the purposes of these Terms and Conditions:</p>
                                    <p class="text-start"><i>Authorized</i> means having official permission or approval.</p>

                                    <p class="text-start"><i>Company</i> (referred to as either "the Company", "We", "Us" or "Our" in this Agreement) refers to Katipunan ng Kabataan Information System website.</p>

                                    <p class="text-start"><i>Content</i> refers to such as text, images, or other information that can be posted, uploaded, linked to, or otherwise made available by You, regardless of the form of that content.</p>

                                    <p class="text-start"><i>Device</i> means any device that can access the Service such as a computer, a cellphone, or a digital tablet.</p>

                                    <p><i>Terms and Conditions</i> (also referred to as "Terms") mean these Terms and Conditions that form the entire agreement between You and the Company regarding the use of the Service.</p>

                                    <p class="text-start"><i>Website</i> refers to Katipunan ng Kabataan Information System.</p>

                                    <p class="text-start"><i>You</i> mean the individual accessing or using the Service, or the company, or otherlegal entity on behalf of which such individual is accessing or using the Service, as applicable.</p>

                                    <h6 class="text-start">Acknowledgment</h6>
                                    <p class="text-start">These are the Terms and Conditions governing the use of this Service and the agreement that operates between You and the Website. These Terms and Conditions set out the rights and obligations of all users regarding the use of the Service.</p>

                                    <p class="text-start">Your access to and use of the Service is conditioned on Your acceptance of and compliance with these Terms and Conditions. These Terms and Conditions apply to all visitors, users, and others who access or use the Service.</p>

                                    <p class="text-start">By accessing or using the Service You agree to be bound by these Terms and Conditions. If You disagree with any part of these Terms and Conditions then You may not access the Service.</p>

                                    <p class="text-start">Your access to and use of the Service is also conditioned on Your acceptance of and compliance with the Privacy Policy of the Company.</p>

                                    <p class="text-start">Our Privacy Policy describes Our policies and procedures on the collection, use, and disclosure of Your personal information when You use the Application or the Website and tells You about Your privacy rights and how the law protects You.</p>

                                    <p class="text-start">Please read Our Privacy Policy carefully before using Our Service.</p>

                                    <ol>
                                        <li>
                                            <b class="text-start">Intellectual Property Rights</b>
                                            <p class="text-start">Other than the content you own, under these Terms, KKIS and/or its licensors own all the intellectual property rights and materials contained in this Website.</p>
                                            <p class="text-start">You are granted a limited license only for the purposes of viewing the material contained on this Website.</p>
                                        </li>

                                        <li>
                                            <b class="text-start">Limitation of Liability</b>
                                            <p class="text-start">In no event shall KKIS and any of its developers be held liable for anything arising out of or in any way connected with your use of this Website whether such liability is under contract. KKIS, including its developers, shall not be held liable for any indirect, consequential, or special liability arising out of or in any way related to your use of this Website.</p>
                                        </li>

                                        <li>
                                            <b class="text-start">Suspension and Termination of Services</b>
                                            <p class="text-start">We may terminate or suspend Your access immediately, without prior notice or liability, for any reason whatsoever, including without limitation if You breach these Terms and Conditions.</p>
                                            <p>Upon termination, Your right to use the Service will cease immediately.</p>
                                        </li>

                                        <li>
                                            <b class="text-start">Changes to These Terms and Conditions</b>
                                            <p class="text-start">At Our sole discretion, we reserve the right to modify or replace these Terms at any time. If a revision is material We will make reasonable efforts to provide at least 30 days notice prior to any new terms taking effect. What constitutes a material change will be determined at Our sole discretion.</p>
                                            <p class="text-start">By continuing to access or use Our Service after those revisions become effective, You agree to be bound by the revised terms. If You do not agree to the new terms, in whole or in part, please stop using the website and the Service.</p>
                                        </li>

                                        <li>
                                            <b class="text-start">Governing Law</b>
                                            <p class="text-start">The laws of the Country, excluding its conflicts of law rules, shall govern these Terms and Your use of the Service. Your application use may also be subject to other local, state, national, or international laws.</p>
                                        </li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Forgot Password -->
                    <div class="card d-flex justify-content-center d-none" id="forgotPassCard">
                        <div class="card-head p-2 mt-2">
                            <div class="d-flex align-items-center justify-content-start headContainer">
                                <div class="d-flex justify-content-center align-items-center w-100 p-0">
                                    <i role="button" class="backIcon fa-solid fa-arrow-left me-3" id="rbackBtn"></i>
                                    <span class="resetPassHead d-flex justify-content-center">Forgot Password</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body m-x mt-1 mb-1">
                            <form id="resetPassForm" action="" method="post">
                                <!-- Fields -->
                                <div class="row gx-3 gy-3 mx-2">
                                    <div class="col-12">
                                        <input class="form-control" type="email" name="email" placeholder="Email" required>
                                    </div>
                                    <div class="col-12">
                                        <input class="form-control" type="password" name="newPassword" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number, one uppercase and lowercase letter, and should be at least 8 or more 
                                        characters" placeholder="New Password" id="new_forgot_pass" required>
                                        <i class="toggle-password far fa-eye-slash" id="toggle_new_forgot_pass" toggle="#password-field"></i>
                                    </div>
                                    <div class="col-12">

                                        <input class="form-control" type="password" name="confirmPassword" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number, one uppercase and lowercase letter, and should be at least 8 or more 
                                        characters" placeholder="Confirm New Password" id="confirm_forgot_pass" required>
                                        <i class="toggle-password far fa-eye-slash" id="toggle_confirm_forgot_pass" toggle="#password-field"></i>
                                    </div>
                                </div>
                                <!-- Reset Pass btn -->
                                <div class="row m-0 mt-2">
                                    <div class="col-12 d-flex justify-content-center align-items-center">
                                        <button class="btn controlBtn rounded-pill mt-3" name="resetBtn" type="submit" form="resetPassForm" id="resetBtn">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


        </div>

        <!--Waves Container-->
        <div>
            <svg class="waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 24 150 28" preserveAspectRatio="none" shape-rendering="auto">
                <defs>
                    <path id="gentle-wave" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z" />
                </defs>
                <g class="parallax">
                    <use xlink:href="#gentle-wave" x="48" y="0" fill="rgba(255,255,255,0.7" />
                    <use xlink:href="#gentle-wave" x="48" y="3" fill="rgba(255,255,255,0.5)" />
                    <use xlink:href="#gentle-wave" x="48" y="5" fill="rgba(255,255,255,0.3)" />
                    <use xlink:href="#gentle-wave" x="48" y="7" fill="#fff" />
                </g>
            </svg>
        </div>
        <!--Waves end-->

    </div>

    <!-- Fundamental Links -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>

    <!-- Custom Script -->
    <script type="text/javascript" src="assets/js/index.js"></script>
    <script type="text/javascript" src="assets/js/formValidation.js"></script>
    <script type="text/javascript" src="assets/js/showPass.js"></script>

</body>

</html>