<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="description" content="">
        <meta name="author" content="">

        <title>The Next Live Concert</title>

        <!-- CSS FILES -->        
        <link rel="preconnect" href="https://fonts.googleapis.com">
        
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;200;400;700&display=swap" rel="stylesheet">
                
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <link href="css/bootstrap-icons.css" rel="stylesheet">

        <link href="css/templatemo-festava-live.css" rel="stylesheet">
<!--

TemplateMo 583 Festava Live

https://templatemo.com/tm-583-festava-live

-->

    </head>
    
    <body>

        <main>

            <header class="site-header">
                <div class="container">
                    <div class="row">
                        
                        <div class="col-lg-12 col-12 d-flex flex-wrap">
                            <p class="d-flex me-4 mb-0">
                                <i class="bi-person custom-icon me-2"></i>
                                <strong class="text-dark">Welcome to The Next Live Concert</strong>
                            </p>
                        </div>

                    </div>
                </div>
            </header>


            <nav class="navbar navbar-expand-lg">
                <div class="container">
                    <a class="navbar-brand" href="index.php">
                        The Next Live Concert
                    </a>

                    <a href="ticket.php" class="btn custom-btn d-lg-none ms-auto me-4">Buy Ticket</a>
    
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
    
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav align-items-lg-center ms-auto me-lg-5">
                            <li class="nav-item">
                                <a class="nav-link click-scroll" href="index.php#section_1">Home</a>
                            </li>
    
                            <li class="nav-item">
                                <a class="nav-link click-scroll" href="index.php#section_2">About</a>
                            </li>
    
                            <li class="nav-item">
                                <a class="nav-link click-scroll" href="index.php#section_3">Artists</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link click-scroll" href="index.php#section_4">Schedule</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link click-scroll" href="index.php#section_5">Pricing</a>
                            </li>
    
                            <li class="nav-item">
                                <a class="nav-link click-scroll" href="index.php#section_6">Contact</a>
                            </li>
                        </ul>

                        <a href="ticket.php" class="btn custom-btn d-lg-block d-none">Buy Ticket</a>
                    </div>
                </div>
            </nav>


            <section class="ticket-section section-padding">
                <div class="section-overlay"></div>

                <div class="container">
                    <div class="row">

                        <div class="col-lg-6 col-10 mx-auto">
                            <form id="ticketForm" class="custom-form ticket-form mb-5 mb-lg-0" method="post" role="form">
                                <h2 class="text-center mb-4">Get started here</h2>

                                <div class="ticket-form-body">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-12">
                                            <input type="text" name="ticket-form-name" id="ticket-form-name" class="form-control" placeholder="Full name" required>
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-12">
                                            <input type="email" name="ticket-form-email" id="ticket-form-email" pattern="[^ @]*@[^ @]*" class="form-control" placeholder="Email address" required>
                                        </div>
                                    </div>

                                    <input type="tel" class="form-control" name="ticket-form-phone" placeholder="Ph 085-456-7890"  required="">

                                    <h6 class="mt-4">Choose Ticket Type</h6>
                                    <div class="row">
                                    <div class="col-lg-6 col-md-6 col-12 mb-3">
                                        <label class="form-control d-flex align-items-center" style="height:56px;">
                                        <input class="form-check-input me-2" type="radio" name="ticket_type" id="ticket_ga" value="GA" checked>
                                        GA (STANDING): <strong class="ms-1">735.000 VND</strong>
                                        </label>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12 mb-3">
                                        <label class="form-control d-flex align-items-center" style="height:56px;">
                                        <input class="form-check-input me-2" type="radio" name="ticket_type" id="ticket_nech" value="NECH_AB">
                                        NẾCH A - B (SEATING): <strong class="ms-1">1.450.000 VND</strong>
                                        </label>
                                    </div>
                                    </div>
                                    <p>Combo 5 – Save 5%: Applicable for purchases of 5 to 9 tickets in a single order.</p>
                                    <p>Group of 10 or more – Save 10%: Applicable for purchases of 10 or more tickets in a single order.</p>
                                    <p>(Valid only for the same ticket category)</p>
                                    <input type="number" name="ticket-form-number" id="ticket-form-number" class="form-control" placeholder="Number of Tickets" required>

                                    <textarea name="ticket-form-message" rows="1" class="form-control" id="ticket-form-message" placeholder="Total Price"></textarea>

                                    <div  id="ticketForm" class="col-lg-4 col-md-10 col-8 mx-auto" method="post">
                                        <button  type="submit" class="form-control">Buy Ticket</button>
                                    </div>
                                </div>
                            </form>
                    </div>
                </div>
            </section>
        </main>


        <footer class="site-footer">
            <div class="site-footer-top">
                <div class="container">
                    <div class="row">

                        <div class="col-lg-6 col-12">
                            <h2 class="text-white mb-lg-0">The Next Live Concert</h2>
                        </div>

                        <div class="col-lg-6 col-12 d-flex justify-content-lg-end align-items-center">
                            <ul class="social-icon d-flex justify-content-lg-end">
                                
    
                                
    
                                <li class="social-icon-item">
                                    <a href="https://www.instagram.com/thenext.liveconcert" target="_blank" class="social-icon-link">
                                        <span class="bi-instagram"></span>
                                    </a>
                                </li>

                                <li class="social-icon-item">
                                    <a href="https://www.youtube.com/@thenextliveconcert" target="_blank" class="social-icon-link">
                                        <span class="bi-youtube"></span>
                                    </a>
                                </li>

                                <li class="social-icon-item">
                                    <a href="https://www.facebook.com/thenextconcert" target="_blank" class="social-icon-link">
                                        <span class="bi-facebook"></span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="row">

                    <div class="col-lg-6 col-12 mb-4 pb-2">
                        <h5 class="site-footer-title mb-3">Links</h5>

                        <ul class="site-footer-links">
                            <li class="site-footer-link-item">
                                <a href="#section_1" class="site-footer-link">Home</a>
                            </li>

                            <li class="site-footer-link-item">
                                <a href="#section_2" class="site-footer-link">About</a>
                            </li>

                            <li class="site-footer-link-item">
                                <a href="#section_3" class="site-footer-link">Artists</a>
                            </li>

                            <li class="site-footer-link-item">
                                <a href="#section_4" class="site-footer-link">Schedule</a>
                            </li>

                            <li class="site-footer-link-item">
                                <a href="#section_5" class="site-footer-link">Pricing</a>
                            </li>

                            <li class="site-footer-link-item">
                                <a href="#section_6" class="site-footer-link">Contact</a>
                            </li>
                        </ul>
                    </div>

                    <div class="col-lg-3 col-md-6 col-12 mb-4 mb-lg-0">
                        <h5 class="site-footer-title mb-3">Have a question?</h5>

                        <p class="text-white d-flex mb-1">
                            <a href="tel: 0868267767" class="site-footer-link">
                                0868267767
                            </a>
                        </p>

                        <p class="text-white d-flex">
                            <a href="mailto:thenextconcert.vn@gmail.com" class="site-footer-link">
                                thenextconcert.vn@gmail.com
                            </a>
                        </p>
                    </div>

                    <div class="col-lg-3 col-md-6 col-11 mb-4 mb-lg-0 mb-md-0">
                        <h5 class="site-footer-title mb-3">Location</h5>

                        <p class="text-white d-flex mt-3 mb-2">
                        The LINC Park City Urban Area, Le Trong Tan Street, Duong Noi Ward</p>

                        <a class="link-fx-1 color-contrast-higher mt-3" href="index.php#section_6" target="_blank">
    <span>Our Maps</span>
    <svg class="icon" viewBox="0 0 32 32" aria-hidden="true"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="16" cy="16" r="15.5"></circle><line x1="10" y1="18" x2="16" y2="12"></line><line x1="16" y1="12" x2="22" y2="18"></line></g></svg>
</a>
                    </div>
                </div>
            </div>

            <div class="site-footer-bottom">
                <div class="container">
                    <div class="row">

                        <div class="col-lg-3 col-12 mt-5">
                            <p class="copyright-text">Copyright © 2036 The Next Live Concert</p>
                        </div>

                        <div class="col-lg-8 col-12 mt-lg-5">
                            <ul class="site-footer-links">
                                <li class="site-footer-link-item">
                                    <a class="site-footer-link" data-bs-toggle="collapse" href="#termsCollapse" role="button" aria-expanded="false" aria-controls="termsCollapse">Terms &amp; Conditions</a>
                                </li>

                                <div class="collapse" id="termsCollapse">
                                    <div class="card card-body" style="max-width: 700px;">
                                        <h5 class="mb-3">Terms &amp; Conditions</h5>
                                        <ol style="padding-left: 18px;">
                                            <li>
                                                <strong>Tickets are only issued via the official online platform CTicket (Ticketvn).</strong>
                                                <br>
                                                Please be cautious of scams and avoid purchasing from third parties to ensure legality, safety, and validity.
                                            </li>
                                            <li>
                                                <strong>The Organizing Committee (OC) will not be responsible for issues arising from ticket purchases outside the official CTicket system.</strong>
                                                <br>
                                                The OC reserves the right to deny entry and will not resolve complaints or disputes related to such purchases.
                                            </li>
                                            <li>
                                                <strong>Each transaction can only purchase a maximum of 10 tickets.</strong>
                                                <br>
                                                There is no limit on the number of transactions per account.
                                            </li>
                                            <li>
                                                <strong>Ticket delivery time:</strong>
                                                <br>
                                                10 minutes per transaction; maximum 5 minutes per payment.
                                            </li>
                                            <li>
                                                <strong>Age regulations:</strong>
                                                <ul>
                                                    <li>Standing area (CA): For attendees 14 years old and above.</li>
                                                    <li>Seating area (NEA - B): For attendees 10 years old and above.</li>
                                                    <li>
                                                        All attendees aged 18 and above must bring a valid ID (Citizen ID/Passport/Driver’s License with photo).
                                                        <br>
                                                        Those under 18 must be accompanied by a parent/guardian with ID, and both must present documents proving their relationship. Each guardian may accompany up to 2 minors.
                                                    </li>
                                                </ul>
                                            </li>
                                            <li>
                                                <strong>The OC reserves the right to check bags and body searches to ensure safety.</strong>
                                                <br>
                                                Anyone refusing to comply will be denied entry and will not be refunded.
                                            </li>
                                            <li>
                                                <strong>Dangerous/prohibited items are strictly forbidden.</strong>
                                                <br>
                                                (weapons, explosives, narcotics, toxic substances, flammable items, large flags, oversized objects, etc.) The OC will deny entry and confiscate such items without compensation.
                                            </li>
                                            <li>
                                                <strong>For security reasons, customers are required to arrive at least 60 minutes before the show.</strong>
                                                <br>
                                                The OC will not be responsible if you miss check-in due to arriving late. Upon entry, attendees must present their ID, valid ticket QR, and follow staff guidance.
                                            </li>
                                            <li>
                                                <strong>Ticket exchange policy:</strong>
                                                <br>
                                                Customers must check details carefully before purchasing. Once purchased, tickets cannot be canceled, refunded, exchanged, or transferred. The OC will not handle cases of lost or damaged tickets. For support with technical issues or duplicate QR codes, please contact Hotline: <a href="tel:1900636665">1900636665</a>.
                                            </li>
                                            <li>
                                                <strong>Tickets are non-refundable, non-transferable, and cannot be altered under any circumstances.</strong>
                                                <br>
                                                Misuse of tickets will be considered invalid.
                                            </li>
                                            <li>
                                                <strong>Tickets are valid for one-time entry only.</strong>
                                                <br>
                                                The OC is not responsible for expired tickets, lost tickets, or duplicate QR codes. Please do not buy from unauthorized sellers. The QR code will be scanned once at check-in; re-entry is not allowed.
                                            </li>
                                            <li>
                                                <strong>Attendees must follow the OC’s instructions, comply with on-site regulations, and not disrupt the show.</strong>
                                                <br>
                                                Violators may be asked to leave without a refund.
                                            </li>
                                            <li>
                                                <strong>By purchasing tickets, customers agree to these Terms &amp; Conditions and the OC’s regulations.</strong>
                                            </li>
                                        </ol>
                                    </div>
                                </div>

                                
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </footer>


        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery.sticky.js"></script>
        <script src="js/custom.js"></script>
        <script src="js/totalprice.js"></script>
        <script>
            document.getElementById('ourMapsBtn').addEventListener('click', function(e) {
                e.preventDefault();
                document.getElementById('section_6').scrollIntoView({behavior: 'smooth'});
            });
        </script>

        <!-- <script>
            $("#ticketForm").on("submit", function(e){
            e.preventDefault();

            // Lấy dữ liệu từ form
            const data = $(this).serialize(); // name, email, phone, TicketForm, number...

            $.post("purchase_process.php", data, function(res){
                try {
                const r = JSON.parse(res);
                if (r.status === "ok") {
                    // Cách 1: chuyển hướng sang trang thanh toán của MoMo
                    window.location.href = r.payUrl;

                    // (Tuỳ chọn) Cách 2: hiện QR nếu có r.qrCodeUrl
                    // $("#qrImg").attr("src", r.qrCodeUrl).show();
                } else {
                    alert("Thanh toán lỗi: " + (r.message || "Unknown"));
                }
                } catch(e){
                alert("Có lỗi xảy ra, vui lòng thử lại.");
                }
            });
            });
        </script> -->

        <!-- <script>
            $("#ticketForm").on("submit", function(e){
            e.preventDefault();
            const btn=$(this).find('button[type="submit"]');
            btn.prop('disabled', true).text('Processing...');
            $.post("purchase_process.php", $(this).serialize(), function(res){
                const r = typeof res==='string' ? JSON.parse(res) : res;
                if (r.status==="ok" && r.payUrl){
                // Nếu đang duyệt trên mobile & có deeplink, ưu tiên deeplink:
                if (/Mobi|Android/i.test(navigator.userAgent) && r.deeplink) {
                    window.location.href = r.deeplink;
                } else {
                    window.location.href = r.payUrl; // dùng NGAY link
                }
                } else {
                alert("Thanh toán lỗi: " + (r.message || "Không nhận được payUrl"));
                btn.prop('disabled', false).text('Buy Ticket');
                }
            }).fail(function(){
                alert("Không kết nối được server.");
                btn.prop('disabled', false).text('Buy Ticket');
            });
            });
        </script> -->

        <script>
$(function(){
  $("#ticketForm").on("submit", function(e){
    e.preventDefault();
    const $btn = $(this).find('button[type="submit"]');
    $btn.prop('disabled', true).text('Processing...');

    $.post("save_ticket.php", $(this).serialize(), function(res){
      const r = (typeof res === 'string') ? JSON.parse(res) : res;
      if (r.status === 'ok') {
        // dùng amount_text server trả về để tránh lệch format
        alert("Đặt vé thành công!\nMã đơn: " + r.order_id + "\nLoại vé: " + r.ticket_type + "\nSố lượng: " + r.quantity + "\nTổng tiền: " + r.amount_text);
        $("#ticketForm")[0].reset();
        $("#ticket-form-message").val('');
      } else {
        alert("Lỗi: " + (r.message || "Không xác định"));
      }
    }).fail(function(){
      alert("Không kết nối được máy chủ.");
    }).always(function(){
      $btn.prop('disabled', false).text('Buy Ticket');
    });
  });
});
</script>





    </body>
</html>