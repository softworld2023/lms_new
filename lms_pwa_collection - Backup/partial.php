<?php
    date_default_timezone_set('Asia/Kuala_Lumpur');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>LMS Collection</title>
        <link rel="icon" type="image/x-icon" href="favicon.ico">
        <link rel="stylesheet" href="include/css/bootstrap.min.css">
        <link rel="stylesheet" href="include/css/style.css">
        <link rel="stylesheet" href="include/fontawesome/css/fontawesome-all.css" />
        <!-- <link rel="manifest" href="manifest.json"> -->

        <style>
            /* Custom CSS for label and input alignment */
            .form-group {
                display: flex;
                flex-direction: column;
                margin-bottom: 20px; /* Add spacing between form groups */
                width: 100%;
            }

            .form-group label {
                font-size: 16px; /* Adjust the font size */
                transition: transform 0.2s ease, color 0.2s ease; /* Add animation */
            }

            .form-group input[type="text"],
            .form-group input[type="password"],
            .form-group input[type="text"]:focus,
            .form-group input[type="password"]:focus {
                border: none; /* Remove default border */
                border-bottom: 1px solid #ccc; /* Add bottom border */
                font-size: 16px; /* Adjust the font size */
                padding: 5px; /* Add padding to inputs */
                transition: border-color 0.2s ease; /* Add border animation */
            }

            /* Highlight label and input on focus */
            .form-group input[type="text"]:focus,
            .form-group input[type="password"]:focus {
                border: none;
            }

            .form-group label.focused {
                transform: translateY(0); /* Reset label position */
                font-size: 14px; /* Shrink label font size */
                color: #007bff; /* Change label color on focus */
            }

            label {
                margin-bottom: 3px;
            }

            .password-container {
                display: flex;
                justify-content: center;
                align-items: center;
            }

            #toggleBtn {
                display: flex;
                justify-content: center;
                align-items: center;
                margin-left: 5px;
                padding: 0;
                width: 35px;
            }

            #login-page, #collection {
                display: flex;
                flex-direction: column;
                justify-content: flex-start;
                align-items: center;
                width: 100vw;
                height: 100vh;
            }

            #form-login {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                height: 100%;
            }

            #content {
                display: flex;
                flex-direction: column;
                justify-content: flex-start;
                align-items: center;
                width: 100%;
                height: 100%;
                overflow-y: auto;
            }

            .input-group-text {
                height: 100%;
                background-color: white;
            }

            .card {
                display: flex;
                justify-content: center;
                align-items: center;
                width: 100%;
                box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
            }

            .card-body {
                display: flex;
                justify-content: space-between;
                align-items: center;
                width: 100%;
            }

            #btn-logout {
                display: flex;
                justify-content: center;
                align-items: center;
            }

            #search-customer-container, .customer-details, #form-collection {
                display: flex;
                justify-content: center;
                align-items: center;
                padding: 10px;
            }

            #search-customer-container > div, .customer-details > div, #form-collection > div {
                display: flex;
                justify-content: center;
                align-items: center;
                width: 100%;
                padding: 5px;
            }

            #btn-search {
                margin: 5px;
            }

            #btn-submit-collection {
                display: flex;
                flex-direction: row;
                justify-content: center;
                align-items: center;
            }

            hr {
                width: 100%;
                margin: 0;
            }

            .customer-details, #form-collection {
                display: flex;
                flex-direction: column;
            }

            .input-group {
                margin: 0 !important;
                padding: 0 !important;
                width: calc(100% + 40px) !important;
            }

            .btn-salary-type, .btn-instalment-type {
                padding: 0 5px;
            }

            #btn-upload-photo {
                display: flex;
                justify-content: center;
                align-items: center;
                padding: 0 5px;
                border-top-right-radius: 0;
                border-bottom-right-radius: 0;
            }

            #input-salary-type {
                padding: 1px 0;
                position: relative;
                top: 2px;
                display: inline-block;
            }

            #filename {
                display: flex;
                justify-content: flex-start;
                align-items: center;
                width: 60%;
                height: 30px;
                padding: 0 5px;
                border-top-right-radius: 4px;
                border-bottom-right-radius: 4px;
                overflow: hidden; /* Hide scrollbar */
                overflow-x: scroll; /* Enable horizontal scrolling */
                scrollbar-width: none;
            }

            @media (orientation: portrait) {
                #search-customer-container {
                    flex-direction: column;
                    width: 100%;
                }

                #search-customer-container div label, .customer-details div label {
                    width: 50% !important;
                }

                #form-collection div .label {
                    width: 140% !important;
                }

                .customer-details, #form-collection {
                    width: 100%;
                }

                #customer-details-landscape {
                    display: none;
                }

                #input-salary-type {
                    width: 80px;
                }

                .label-instalment {
                    flex-direction: column;
                }

                .label-instalment > div {
                    width: 100%;
                }

                #instalment_month {
                    width: 50%;
                    margin-top: 5px;
                    margin-right: 5px;
                }

                .label-tepi1 > span, .label-tepi2 > span {
                    width: 50%;
                }

                #tepi1_month, #tepi2_month {
                    width: 50%;
                    margin-right: 5px;
                }
            }

            @media (orientation: landscape) {
                #search-customer-container {
                    flex-direction: row;
                    width: 80%;
                }

                #search-customer-container div label, .customer-details div label, #form-collection div .label {
                    width: 80%;
                    margin-right: 10px;
                }

                #btn-search {
                    margin-left: 20px;
                }

                .customer-details, #form-collection {
                    width: 80%;
                }

                #customer-details-portrait {
                    display: none;
                }

                #input-salary-type {
                    width: 120px;
                }

                .instalment-type-container {
                    width: 70%;
                }

                .instalment-month-container {
                    width: 30%;
                }

                .label-tepi1 > span, .label-tepi2 > span {
                    width: 70%;
                }

                #tepi1_month, #tepi2_month {
                    width: 30%;
                }
            }
        </style>
    </head>
    <body>
        <div id="login-page" style="display: none;">
            <div class="app-bar p-3 pl-3 bg-secondary">
                <span id="app-bar-title">LMS Collection</span>
                <a id="install-btn" onclick="addToHomeScreen();">
                    <svg height="24" width="24" version="1.1" id="_x32_" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512"  xml:space="preserve">
                        <g>
                            <path fill="#ffffff" d="M426.537,0H179.641c-22.243,0-40.376,18.175-40.376,40.401v129.603h25.221V69.106h277.206V424.46H164.485
                                v-83.887h-25.221v131.034c0,22.192,18.133,40.392,40.376,40.392h246.896c22.192,0,40.375-18.2,40.375-40.392v-129.03V40.401
                                C466.912,18.175,448.728,0,426.537,0z M303.08,478.495c-9.174,0-16.636-7.47-16.636-16.661c0-9.183,7.462-16.653,16.636-16.653
                                c9.158,0,16.686,7.47,16.686,16.653C319.766,471.025,312.247,478.495,303.08,478.495z"/>
                            <polygon fill="#ffffff" points="225.739,335.774 358.778,255.289 225.739,174.804 225.739,221.11 45.088,221.11 45.088,289.468 
                                225.739,289.468 	"/>
                        </g>
                    </svg>
                </a>
            </div>
            <form id="form-login">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="password-container">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control mr-3" id="password" name="password" required>
                    </div>
                    <span id="toggleBtn" class="btn text-secondary" onclick="togglePasswordVisibility();">
                        <i id="eye-icon" class="fa fa-eye" aria-hidden="true"></i>
                    </span>
                </div>
                <div class="form-group">
                    <label for="branch">Branch</label>
                    <input type="text" class="form-control" id="branch" name="branch" style="text-transform: uppercase;" required>
                </div>
                <button type="submit" class="btn btn-secondary" id="btn-login" style="width: 100%;">Login</button>
            </form>
        </div>
        <div id="collection" style="display: none;">
            <div class="app-bar p-3 pl-3" style="background-color: purple; height: fit-content;">
                <div class="d-flex flex-column flex-sm-row gap-2">
                    <span id="collection-title" class="app-bar-title"></span>
                    <div>
                        <a href="index.php" style="margin-left: 10px; text-decoration: none;">
                            <img src="../img/back-button.png" style="width: 24px;" title="Collection">
                        </a>
                        <a href="../lms_dev/payment/add_monthly.php" target="_blank" style="margin-left: 10px; text-decoration: none;">
                            <img src="../img/apply-loan/add-btn.jpg" title="Add Monthly">
                        </a>
                        <a href="../lms_dev/payment/add_instalment.php" target="_blank" style="margin-left: 10px; text-decoration: none;">
                            <img src="../img/c.png" style="width: 31px;" title="Add Instalment">
                        </a>
                        <!-- <a href="bad_debt_monthly.php"  target="_blank" style="margin-left: 10px; text-decoration: none;" >
                            <img src="../img/error.png" title="Bad Debt" style="width: 24px;">
                        </a> -->
                        <a href="collection_bad_debt.php" style="margin-left: 10px; text-decoration: none;" >
                            <img src="../img/error.png" title="Bad Debt" style="width: 24px;">
                        </a>
                        <a href="collection_monthly.php" style="margin-left: 10px; text-decoration: none;" >
                            <img src="../img/pay_monthly.png" title="Collection - Monthly" style="width: 24px;">
                        </a>
                        <a href="../lms_pwa_collection/apply_loan.php" target="_blank" style="margin-left: 10px; text-decoration: none;">
                            <img src="../img/customers-icon.png" style="width: 31px;" title="Add Customer">
                        </a>
                        <a href="discount.php" style="margin-left: 10px; text-decoration: none;">
                            <img src="../img/discount-icon.png" style="width: 27px;" title="Add Discount">
                        </a>
                        <a href="short.php" style="margin-left: 10px; text-decoration: none;">
                            <img src="../img/shorts-icon.png" style="width: 27px;" title="Add Short">
                        </a>
                    </div>
                    <!-- <a href="partial.php" style="margin-left: 10px; text-decoration: none;">
                        <img src="../img/payment.png" style="width: 30px;" title="Add Partial">
                    </a> -->
                </div>
                <a id="btn-logout" onclick="logout();">
                    <svg xmlns="http://www.w3.org/2000/svg" style="fill: white" width="24" height="24" viewBox="0 -960 960 960"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z"/></svg>
                </a>
            </div>
            <div id="search-customer-container">
                <div>
                    <label for="loan_code">Loan Code</label>
                    <input type="text" class="form-control" list="loan_codes" id="loan_code" name="loan_code" autocomplete="off" required>
                    <datalist id="loan_codes">
                    </datalist>
                </div>
                <div>
                    <label for="customer_code">Customer ID</label>
                    <input type="text" class="form-control" list="customer_codes" id="customer_code" name="customer_code" autocomplete="off" required>
                    <datalist id="customer_codes">
                    </datalist>
                </div>
                <div>
                    <button type="button" class="btn btn-secondary" id="btn-search" style="width: 49%;" onclick="search();">Search</button>
                    <button type="button" class="btn btn-outline-secondary" id="btn-reset" style="width: 49%;" onclick="reset();">Reset</button>
                </div>
            </div>
            <hr>
            <div id="content">
                <div id="customer-details-portrait" class="customer-details">
                    <div style="justify-content: flex-start; width: 100%; padding: 0;"><b>Customer Details<span class="loan_code"></span></b></div>
                    <div>
                        <label>Customer ID</label>
                        <input type="text" class="form-control customer_code" readonly>
                    </div>
                    <div>
                        <label>Name</label>
                        <input type="text" class="form-control customer_name" readonly>
                    </div>
                    <!-- <div>
                        <label>I/C No.</label>
                        <input type="text" class="form-control customer_ic" readonly>
                    </div> -->
                </div>
                <div id="customer-details-landscape" class="customer-details">
                    <div class="mb-2" style="justify-content: flex-start; width: 100%; padding: 5px;"><b>Customer Details<span class="loan_code"></span></b></div>
                    <div>
                        <div style="width: 40%;">
                            <label>Customer ID</label>
                            <input type="text" class="form-control customer_code" id="customer_code" readonly>
                        </div>
                        <div class="ms-5" style="width: 60%;">
                            <label style="width: 40%;">Name</label>
                            <input type="text" class="form-control customer_name" readonly>
                        </div>
                    </div>
                    <div>
                        <div style="width: 40%;">
                        </div>
                        <div class="ms-5" style="width: 60%;">
                            <label style="width: 40%;">I/C No.</label>
                            <input type="text" class="form-control customer_ic" readonly>
                        </div>
                    </div>
                </div>
                <hr>
                <form id="form-collection">
                    <input type="hidden" id="salary_type" name="salary_type" value="Gaji">
                    <input type="hidden" id="instalment_type" name="instalment_type" value="Half Month">
                    <div>
                        <div class="label">
                            <span class="btn btn-secondary btn-salary-type" id="btn-gaji">Gaji</span>&nbsp;/&nbsp; 
                            <span class="btn btn-outline-secondary btn-salary-type">Adv</span>&nbsp;/&nbsp;
                            <span class="btn btn-outline-secondary btn-salary-type">Bonus</span>&nbsp;/&nbsp;
                            <input type="text" class="form-control" id="input-salary-type" maxlength="20">
                        </div>
                        <div class="input-group">
                            <span class="input-group-text">RM</span>
                            <input type="number" class="form-control" id="salary" name="salary" min="0" step="0.1" required>
                        </div>
                    </div>
                    <div>
                        <div class="label label-instalment" style="display: flex; align-items: center;">
                            <div class="instalment-type-container">
                                <span class="btn btn-secondary btn-instalment-type">Half Month</span>
                                <!-- &nbsp;/&nbsp; -->
                                <!-- <span class="btn btn-outline-secondary btn-instalment-type">Half Month</span>&nbsp;/&nbsp;
                                <span class="btn btn-outline-secondary btn-instalment-type">Short</span> -->
                            </div>
                            <div class="instalment-month-container" style="display: flex; justify-content: flex-end;">
                                <input type="month" class="form-control" id="instalment_month" name="instalment_month" value="<?php echo date('Y-m'); ?>">
                            </div>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text">RM</span>
                            <input type="number" class="form-control" id="instalment" name="instalment" min="0" step="0.1" required>
                        </div>
                    </div>
                    <div>
                        <div class="label label-tepi1" style="display: flex; align-items: center;">
                            <span>Tepi 1</span>
                            <input type="month" class="form-control" id="tepi1_month" name="tepi1_month" value="<?php echo date('Y-m'); ?>">
                        </div>
                        <div class="input-group">
                            <span class="input-group-text">RM</span>
                            <input type="number" class="form-control" id="tepi1" name="tepi1" min="0.00" step="0.1" required>
                        </div>
                    </div>
                    <div>
                        <div class="label label-tepi2" style="display: flex; align-items: center;">
                            <span>Tepi 2</span>
                            <input type="month" class="form-control" id="tepi2_month" name="tepi2_month" value="<?php echo date('Y-m', strtotime('+1 month')); ?>">
                        </div>
                        <div class="input-group">
                            <span class="input-group-text">RM</span>
                            <input type="number" class="form-control" id="tepi2" name="tepi2" min="0.00" step="0.1" required>
                        </div>
                    </div>
                    <div>
                        <div class="label">Tepi 2 (Bunga)</div>
                        <div class="input-group">
                            <span class="input-group-text">RM</span>
                            <input type="number" class="form-control" id="tepi2_bunga" name="tepi2_bunga" min="0.00" step="0.1" required>
                        </div>
                    </div>
                    <div>
                        <div class="label">Balance Diterima</div>
                        <div class="input-group">
                            <span class="input-group-text">RM</span>
                            <input type="number" class="form-control" id="balance_received" name="balance_received" min="0.00" step="0.1" required>
                        </div>
                    </div>
                    <div>
                        <div class="label">Upload receipt photo</div>
                        <div class="input-group" style="display: flex; flex-direction: row; overflow: auto;">
                            <label for="photo" style="width: 40% !important;">
                                <span id="btn-upload-photo" class="btn btn-secondary" style="height: 30px;">Browse</span>
                            </label>
                            <span id="filename" class="border border-secondary text-secondary"></span>
                            <input type="file" class="form-control form-control-sm" id="photo" name="photo" accept="image/*" required hidden>
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-secondary" id="btn-submit-collection" style="width: 50%;">
                            <span>Submit</span>
                            <div id="loading" style="display: none;">
                                <div class="loading-dots">
                                    <div class="dot"></div>
                                    <div class="dot"></div>
                                    <div class="dot"></div>
                                </div>
                            </div>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div id="snackbar">Snackbar Message</div>
        <div id="overlay"></div>

        <script src="include/js/jquery-2.1.3.min.js"></script>
        <script src="include/js/functions.js"></script>
        <script src="include/js/moment-with-locales.min.js"></script>
        <script src="include/js/tempusdominus-bootstrap-4.min.js"></script>

        <script>
            // Store the deferred prompt for later use
            let deferredPrompt;

            const username = getCookie('lms_collection_username') ?? '';
            const branch = getCookie('lms_collection_branch') ?? '';

            $(document).ready(function() {
                // Add focused class to label when input is focused
                $('.form-group input[type="text"], .form-group input[type="password"]').on('focus', function() {
                    $(this).parent().find('label').addClass('focused');
                });
                $('.form-group input[type="text"], .form-group input[type="password"]').on('blur', function() {
                    if ($(this).val() === '') {
                        $(this).parent().find('label').removeClass('focused');
                    }
                });

                if (username != '') {
                    goToCollection();
                } else {
                    goToLogin();
                }

                // showSnackbar('username: ' + username);
            });

            // Check if the PWA is running in standalone mode (installed)
            if (window.matchMedia('(display-mode: standalone)').matches || checkDevicePlatform() == 'iOS') {
                $('#install-btn').hide(); // Hide the "Add to Home Screen" button
            }

            // Listen for the beforeinstallprompt event
            window.addEventListener('beforeinstallprompt', (event) => {
                // Prevent the browser's default install prompt
                event.preventDefault();

                // Store the event for later use
                deferredPrompt = event;

                // Show your custom "Add to Home Screen" button
                $('#install-btn').show();
            });


            $('.btn-salary-type').on('click', function() {
                let text = $(this).text();
                // console.log(text);
                $('#salary_type').val(text);
                $('.btn-salary-type').removeClass('btn-secondary').addClass('btn-outline-secondary');
                $(this).removeClass('btn-outline-secondary').addClass('btn-secondary');
            });

            $('#input-salary-type').on('click', function() {
                $('#salary_type').val($(this).val());
                $('.btn-salary-type').removeClass('btn-secondary').addClass('btn-outline-secondary');
            });

            $('#input-salary-type').on('input', function() {
                $('#salary_type').val($(this).val());
            });

            $('.btn-instalment-type').on('click', function() {
                let text = $(this).text();
                // console.log(text);
                $('#instalment_type').val(text);
                $('.btn-instalment-type').removeClass('btn-secondary').addClass('btn-outline-secondary');
                $(this).removeClass('btn-outline-secondary').addClass('btn-secondary');
            });

            $('#input-salary-type').on('blur', function() {
                if ($(this).val() == '') {
                    setTimeout(() => {
                        let isBtnSalaryTypeClicked = false;
                        $('.btn-salary-type').each(function(index) {
                            if ($(this).hasClass('btn-secondary')) {
                                isBtnSalaryTypeClicked = true;
                            }
                        });
                        if (isBtnSalaryTypeClicked == false) {
                            let btn = $('.btn-salary-type').first();
                            $('#salary_type').val(btn.html());
                            btn.removeClass('btn-outline-secondary').addClass('btn-secondary');
                        }
                    }, 100);
                }
            });

            $('#tepi2').on('input', function() {
                let tepi2_bunga = $(this).val() != '' ? $(this).val() * 0.1 + 5 : 0;
                $('#tepi2_bunga').val(tepi2_bunga);
            });

            $('#instalment, #tepi2').on('blur', function() {
                if ($(this).val() == '') {
                    $(this).val(0);
                }
            });

            $('#salary, #instalment, #tepi1, #tepi2, #tepi2_bunga').on('input', function() {
                let balanceReceived = $('#salary').val() - $('#instalment').val() - $('#tepi1').val() - $('#tepi2_bunga').val();
                $('#balance_received').val(balanceReceived);
            });

            $('#photo').on('change', function() {
                let filename = $(this)[0].files[0].name;
                console.log(filename);
                $('#filename').html(filename);
            });

            // Prevent form submission on Enter key press
            $('#form-collection').on('submit', function(event) {
                event.preventDefault(); // Prevent the default form submission
            });

            $('#btn-submit-collection').on('click', function(event) {
                event.preventDefault(); // Prevent the default form submission
                let branch = getCookie('lms_collection_branch') ?? '';
                let loanCode = $('.loan_code').html();
                loanCode = loanCode.replace(' - ', '');
                let instalment = parseFloat($('#instalment').val()) || 0;
                // let balanceReceived = parseFloat($('#balance_received').val()) || 0;
                // if (balanceReceived > instalment) {
                //     showSnackbar(`Balance received cannot exceed of the half month (RM ${instalment.toFixed(2)}).`);
                //     return; // Stop submission
                // }

                if (instalment < 0 || instalment == 0) {
                    showSnackbar(`Amount cannot less than 0`);
                    return; // Stop submission
                }

                // if (balanceReceived < instalment) {
                //     showSnackbar(`Balance received cannot less than of the half month (RM ${instalment.toFixed(2)}).`);
                //     return; // Stop submission
                // }

                // console.log(loanCode);

                let photo = $('#photo');

                let formData = new FormData();
                formData.append('branch', branch);
                formData.append('loan_code', loanCode);
                formData.append('salary', $('#salary').val());
                formData.append('salary_type', $('#salary_type').val());
                formData.append('instalment', $('#instalment').val());
                formData.append('instalment_type', $('#instalment_type').val());
                formData.append('instalment_month', $('#instalment_month').val());
                formData.append('tepi1', $('#tepi1').val());
                formData.append('tepi1_month', $('#tepi1_month').val())
                formData.append('tepi2', $('#tepi2').val());
                formData.append('tepi2_month', $('#tepi2_month').val());
                formData.append('tepi2_bunga', $('#tepi2_bunga').val());
                formData.append('balance_received', $('#balance_received').val());
                formData.append('customercode', $('.customer_code').val());

                if (photo.val() != '') {
                    // console.log(photo[0].files[0]);
                    formData.append('photo', photo[0].files[0]);
                }

                showOverlay();
                $('#loading').show();
                $('#btn-submit-collection > span').html('Submitting');

                $.ajax({
                    url: 'collection.php',
                    type: 'POST',
                    data: formData,
                    processData: false, // Prevent jQuery from automatically processing the data
                    contentType: false, // Prevent jQuery from automatically setting the content type
                    dataType: 'text',
                    success: function(response) {
                        let message = response.trim();
                        if (message == 'success') {
                            showSnackbar(`Collection for ${loanCode} recorded successfully.`);
                            reset();
                        } else if (message == 'fail') {
                            showSnackbar(`Fail to record collection for ${loanCode}.`);
                        } else {
                            showSnackbar(message);
                        }

                        hideOverlay();
                        $('#loading').hide();
                        $('#btn-submit-collection > span').html('Submit');
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });

            function search() {
                $.ajax({
                    url: 'get_customer_details_ajax.php',
                    type: 'POST',
                    data: {
                        branch: branch,
                        loan_code: $('#loan_code').val(),
                        customer_code: $('#customer_code').val()
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log(response);
                        $('.customer_code').val(response.customer_code ? response.customer_code : '');
                        $('.customer_name').val(response.customer_name ? response.customer_name : '');
                        $('.customer_ic').val(response.customer_ic ? response.customer_ic : '');
                        $('#instalment').val(response.loan_monthly ? (response.loan_monthly / 2).toFixed(2) : '0.00'); 
                        $('#salary').val(0);
                        $('#tepi1').val(0);
                        $('#tepi2').val(0);
                        $('#tepi2_bunga').val(0);
                        $('#balance_received').val(0);

                        if (response.customer_id && $('#loan_code').val() !== '') {                            
                            $('a[href^="bad_debt_monthly.php"]').attr('href', `bad_debt_monthly.php?id=${response.customer_id}&loan_code=${$('#loan_code').val()}`);
                            $('a[href^="discount.php"]').attr('href', `discount.php?id=${response.customer_id}&loan_code=${$('#loan_code').val()}`);
                        }
                        if (response.customer_id) {
                            $('.loan_code').html(response.loan_code ? ` - ${response.loan_code}` : '');
                            $('a[href^="../lms_dev/payment/add_monthly.php"]').attr('href', `../lms_dev/payment/add_monthly.php?id=${response.customer_id}`);
                            $('a[href^="../lms_dev/payment/add_instalment.php"]').attr('href', `../lms_dev/payment/add_instalment.php?id=${response.customer_id}`);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        // showSnackbar(error);
                    }
                });
            }

            function reset() {
                console.log('reset');
                $('#loan_code').val('');
                $('#customer_code').val('');
                $('.loan_code').html('');
                $('.customer_name').val('');
                $('.customer_code').val('');
                $('.customer_ic').val('');
                $('.company').val('');
                $('.loan_amount').val('');
                $('.loan_monthly').val('');
                $('.loan_period').val('');
                $('.loan_total').val('');
                $('#instalment_month').val('<?php echo date('Y-m'); ?>');
                $('#salary').val('');
                $('.btn-salary-type').first().click();
                $('#instalment').val('');
                $('.btn-instalment-type').first().click();
                $('#tepi1').val('');
                $('#tepi1_month').val('<?php echo date('Y-m'); ?>');
                $('#tepi2').val('');
                $('#tepi2_month').val('<?php echo date('Y-m', strtotime('+1 month')); ?>');
                $('#tepi2_bunga').val('');
                $('#balance_received').val('');
                $('#photo').val('');
                $('#filename').html('');

                // Reset the URLs back to default values
                $('a[href^="../lms_dev/payment/add_monthly.php"]').attr('href', '../lms_dev/payment/add_monthly.php');
                $('a[href^="../lms_dev/payment/add_instalment.php"]').attr('href', '../lms_dev/payment/add_instalment.php');
                $('a[href^="bad_debt_monthly.php"]').attr('href', `bad_debt_monthly.php`);
                $('a[href^="discount.php"]').attr('href', `discount.php`);
            }

            function goToLogin() {
                reset();
                $('#collection').hide();
                $('#login-page').show();
            }

            function goToCollection() {
                $('#login-page').hide();
                $('#collection').show(0, function() {
                    let username = getCookie('lms_collection_username') ?? '';
                    let branch = getCookie('lms_collection_branch') ?? '';
    
                    $('#collection-title').html(`Collection - ${branch.toUpperCase()}`);

                    $.ajax({
                        url: 'get_customer_list_ajax.php',
                        type: 'POST',
                        data: {
                            branch: branch
                        },
                        dataType: 'json',
                        success: function(response) {
                            // console.log(response);
                            let loanCodeArr = response.loan_code;
                            let customerCodeArr = response.customer_code;
                            let html = '';

                            if (loanCodeArr) {
                                loanCodeArr.forEach(loanCode => {
                                    html += `<option value="${loanCode}">`;
                                });
                            }
                            $('#loan_codes').html(html);

                            html = '';
                            if (customerCodeArr) {
                                customerCodeArr.forEach(customerCode => {
                                    html += `<option value="${customerCode}">`;
                                });
                            }
                            $('#customer_codes').html(html);
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                            // showSnackbar(error);
                        }
                    });
                });
            }

            function togglePasswordVisibility() {
                var x = document.getElementById('password');
                let toggleBtn = $('#toggleBtn');
                let eyeIcon = $('#eye-icon');
                if (x.type === 'password') {
                    x.type = 'text';
                    eyeIcon.removeClass('fa-eye');
                    eyeIcon.addClass('fa-eye-slash');
                } else {
                    x.type = 'password';
                    eyeIcon.removeClass('fa-eye-slash');
                    eyeIcon.addClass('fa-eye');
                }
            }

            function addToHomeScreen() {
                console.log('addToHomeScreen');
                // Show the browser's install prompt
                if (deferredPrompt) {
                    deferredPrompt.prompt();
                    deferredPrompt.userChoice.then((choiceResult) => {
                        if (choiceResult.outcome === 'accepted') {
                        console.log('User accepted the install prompt');
                        } else {
                        console.log('User dismissed the install prompt');
                        }
                        deferredPrompt = null;
                    });
                }
            }

            function logout() {
                // Clear cookies
                document.cookie = 'lms_collection_username=; expires=Thu, 01 Jan 1970 00:00:00 UTC';
                document.cookie = 'lms_collection_branch=; expires=Thu, 01 Jan 1970 00:00:00 UTC';

                goToLogin();
            }

            function showOverlay() {
                $('#overlay').show();
            }

            function hideOverlay() {
                $('#overlay').hide();
            }

            $('#btn-short').on('click', function () {
                $('#balance_received').css('opacity', 0);
                $('#balance_received').closest('.input-group').prev('.label').css('opacity', 0);
                $('#balance_received').closest('.input-group').css('opacity', 0);
            });

            $('#btn-gaji').on('click', function () {
                $('#balance_received').css('opacity', 1);
                $('#balance_received').closest('.input-group').prev('.label').css('opacity', 1);
                $('#balance_received').closest('.input-group').css('opacity', 1);
            });

            $('#input-salary-type').on('click', function () {
                $('#balance_received').css('opacity', 1);
                $('#balance_received').closest('.input-group').prev('.label').css('opacity', 1);
                $('#balance_received').closest('.input-group').css('opacity', 1);
            });
        </script>
    </body>
</html>